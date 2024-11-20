<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TemplateController;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\User;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\Design;
use App\Http\Controllers\ProjectController;
use App\Notifications\ReceiptNotification;
use App\Http\Controllers\SubscriptionController;
use App\Services\TinkoffService;
use Illuminate\Support\Facades\Log;
use App\Models\Foundation;
use App\Jobs\FoundationOrderFileJob;
use App\Jobs\GenerateOrderExcelJob;


class OrderController extends Controller
{
    public function processMembershipOrder(Request $request)
    {
        //assign or create user
        if (Auth::check() && $request->input('logged_in')) {
            $user = Auth::user();
        } elseif (!$request->input('logged_in')) {
            $registerController = new RegisterController();
            $user = $registerController->create($request, true);
            $request->merge(['user_id' => $user->id]);
        } else {
            $user = null;
        }

        //$price_type_id = $request->input('price_type_id');
        $price_type_id = 3;
        $price_type = PricePlan::findOrFail($price_type_id);

        $orderType = 'membership';
        
        
        if (env('PAYMENT_PROVIDER') === 'TEST-POS') {
            $result = [
                    'paymentUrl' => route('payment.set.status', ['payment_status' => 'success', 'order_id' => base64_encode("M" . $request->input('inn') . '_' . date('Ymd'))]),
                    'payment_id' => $payment_reference
            ];
        } else {
            try {
                $tinkoff = app(TinkoffService::class);
                    //dd($request->all());
                    $result = $tinkoff->initPayment([
                        'amount' => $price_type->price*100,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'orderId' => "M" . $request->input('inn') . '_' . date('Ymd'),
                        'description' => "Подписка на СТРОЙКА.com на 30 дней",
                    ]);
                } catch (\Exception $e) {
                    Log::error('Tinkoff payment error: ' . $e->getMessage());
                    return response()->json(['error' => 'Payment system error'], 500);
            }
        }
        $registerController->create($request, true);
    }

    public function processFoundationOrder(Request $request)
    {
        //assign or create user
        if (Auth::check() && $request->input('logged_in')) {
            $user = Auth::user();
        } elseif ($request->input('user_id')) {
            $user = User::find($request->input('user_id'));
        } elseif (!$request->input('logged_in')) {
            $registerController = new RegisterController();
            $user = $registerController->create($request, true);
            $request->merge(['user_id' => $user->id]);
        } else {
            return response()->json(['error' => 'Logged in user check failed'], 401);
        }

        $foundationId = $request->input('foundation_id');
        $cellMappings = $request->input('foundation_data');

        
        $foundation = Foundation::findOrFail($foundationId);

        $orderType = 'foundation';
        $projectController = new ProjectController();
        $human_ref = $projectController->generateHumanReference($foundation->id, $orderType);
        $payment_amount = $request->input('payment_amount');
        $payment_reference = (string)(random_int(1000000000, 9999999999));
        $is_example = 1;
        
        if ($request->input('payment_amount') > 0) {
            
            $is_example = 0;

            if (env('PAYMENT_PROVIDER') === 'TEST-POS') {
                $result = [
                    'paymentUrl' => route('payment.set.status', ['payment_status' => 'success', 'order_id' => base64_encode($human_ref)]),
                    'payment_id' => $payment_reference
                ];
            } else {
                try {
                    $tinkoff = app(TinkoffService::class);
                    //dd($request->all());
                    $result = $tinkoff->initPayment([
                        'amount' => $request->input('payment_amount')*100,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'orderId' => $human_ref,
                        'description' => "Смета на фундамент " . $foundation->site_title,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Tinkoff payment error: ' . $e->getMessage());
                    return response()->json(['error' => 'Payment system error'], 500);
                }
            }
        } else {
            $result = [
                'paymentUrl' => route('payment.set.status', ['payment_status' => 'success', 'order_id' => base64_encode($human_ref)]),
                'payment_id' => $payment_reference
            ];
        }
        // Create a new project for the foundation order
        $project = Project::create([
            'user_id' => $user->id,
            'human_ref' => $human_ref,
            'order_type' => $orderType,
            'ip_address' => $request->ip(),
            'is_example' => $is_example,
            'payment_reference' => $payment_reference,
            'payment_amount' => $payment_amount,
            'foundation_id' => $foundation->id,
            'selected_configuration' => $cellMappings,
        ]);

        // Dispatch the job to generate the foundation order file
        FoundationOrderFileJob::dispatch($project)->onConnection('sync');
        GenerateOrderExcelJob::dispatch($project->id)->onConnection('sync');

        $description = $foundation->site_title;
        $paymentUrl = $result['paymentUrl'];
        $project->payment_provider = env('PAYMENT_PROVIDER');
        $project->payment_link = $paymentUrl;
        $project->payment_reference = $payment_reference;
        $project->payment_status = 'pending';
        $project->price_type = 'foundation_example_labour';
        $project->save();
        //$user->notify(new ReceiptNotification($project, $design, $user->email));
        return response()->json([
            'success' => true,
            'paymentUrl' => $paymentUrl
        ]);
    }

    public function processProjectSmetaOrder(Request $request)
    {

        //assign or create user
        if (Auth::check() && $request->input('logged_in')) {
            $user = Auth::user();
        } elseif ($request->input('user_id')) {
            $user = User::find($request->input('user_id'));
        } elseif (!$request->input('logged_in')) {
            $registerController = new RegisterController();
            $user = $registerController->create($request, true);
            $request->merge(['user_id' => $user->id]);
        } else {
            return response()->json(['error' => 'Logged in user check failed'], 401);
        }
        $price_type = $request->input('price_type');
        $payment_reference = (string)(random_int(1000000000, 9999999999));
        if (strpos($price_type, 'labour') !== false) {
            $price_type_label = 'Материалы и работы';
        } else {
            $price_type_label = 'Только материалы';
        }
        $request->merge(['price_type_label' => $price_type_label]);
        //assign design
        $design = Design::find($request->input('design_id'));
        $designTitle = $design->title;
        $orderDesignTitle = "Смета по проекту ($price_type_label) " . $designTitle;
        
        //create project
        $projectController = new ProjectController();
        $orderId = $projectController->createSmetaOrder($request);
        $project = Project::find($orderId);
        $payment_amount = $request->input('payment_amount');
        
        if ($request->input('payment_amount') > 0) {
            
            if (env('PAYMENT_PROVIDER') === 'TEST-POS') {
                $result = [
                    'paymentUrl' => route('payment.set.status', ['payment_status' => 'success', 'order_id' => base64_encode($project->human_ref)]),
                    'payment_id' => $payment_reference
                ];
            } else {
                try {
                    $tinkoff = app(TinkoffService::class);
                    //dd($request->all());
                    $result = $tinkoff->initPayment([
                        'amount' => $request->input('payment_amount')*100,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'orderId' => $project->human_ref,
                        'description' => $orderDesignTitle,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Tinkoff payment error: ' . $e->getMessage());
                    return response()->json(['error' => 'Payment system error'], 500);
                }
            }
        } else {
            $result = [
                'paymentUrl' => route('payment.set.status', ['payment_status' => 'success', 'order_id' => base64_encode($project->human_ref)]),
                'payment_id' => $payment_reference
            ];
        }

        $description = $orderDesignTitle;
        $paymentUrl = $result['paymentUrl'];
        $project->payment_provider = env('PAYMENT_PROVIDER');
        $project->payment_link = $paymentUrl;
        $project->payment_reference = $payment_reference;
        $project->payment_status = 'pending';
        $project->price_type = $price_type;
        $project->save();
        
        $urlcontent = base64_encode($paymentUrl);
        return response()->json([
            'success' => true,
            'paymentUrl' => $paymentUrl,
            'urlcontent' => $urlcontent
        ]);
    }

    public function processExampleSmetaOrder(Request $request)
    {
        //assign or create user

        if (Auth::check() && $request->input('logged_in')) {
            $user = Auth::user();
        } elseif (!$request->input('logged_in')) {
            $registerController = new RegisterController();
            $user = $registerController->create($request, true);
            $request->merge(['user_id' => $user->id]);
        } else {
            return response()->json(['error' => 'Logged in user check failed'], 401);
        }
        $price_type = $request->input('price_type');
        $price_type_label = $price_type === 'material' ? 'Только материалы' : ($price_type === 'total' ? 'Материалы и работы' : 'Материалы, работы и доставка');
        //assign design
        $design = Design::find($request->input('design_id'));
        $designTitle = $design->title;
        $orderDesignTitle = "Смета по проекту ($price_type_label) " . $designTitle;
        
        //create project
        $projectController = new ProjectController();
        $orderId = $projectController->createSmetaOrder($request);
        $project = Project::find($orderId);
        $payment_amount = $request->input('payment_amount');
        
        $description = $orderDesignTitle;
        $payment_reference = (string)(random_int(1000000000, 9999999999));
        $paymentUrl = "example";
        $project->payment_provider = "example";
        $project->payment_link = $paymentUrl;
        $project->payment_reference = $payment_reference;
        $project->payment_status = 'error';
        $project->price_type = $price_type;
        $project->is_example = true;
        $project->save();
        return response()->json([
            'success' => true,
            'paymentUrl' => route('payment.set.status', ['payment_status' => 'success', 'order_id' => base64_encode($project->human_ref)])
        ]);
    }

    public function viewFiscalReceipt($id)
    {
        $project = Project::where('payment_reference', $id)->firstOrFail();
        $design = Design::find($project->design_id);
        $user_email = $project->user->email;
        return view('fiscal-receipt', compact('project', 'design', 'user_email'));
    }

    public function viewGeneralReceipt($id)
    {
        $project = Project::where('payment_reference', $id)->firstOrFail();
        $design = Design::find($project->design_id);
        $user_email = $project->user->email;
        return view('general-receipt', compact('project', 'design', 'user_email'));
    }

    public function yandexPayCallback($orderId, $payment_reference, $description, $payment_amount)
    {
        $isSandbox = env('YANDEX_PAY_SANDBOX');
        if ($isSandbox) {
            $baseUrl = 'https://sandbox.pay.yandex.ru';
        } else {
            $baseUrl = 'https://pay.yandex.ru';
        }
        $orderId = $orderId;
        $price = $payment_amount;
        $title = $description;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Api-Key cc08ce31-8375-439d-86f1-1201533f53e7'
        ])->post("{$baseUrl}/api/merchant/v1/orders", [
            'cart' => [
                'items' => [
                    [
                        'productId' => "101",
                        'quantity' => [
                            'count' => 1
                        ],
                        'title' => $title,
                        'unitPrice' => $price,
                        'total' => $price
                    ]
                ],
                'total' => [
                    'amount' => $price
                ]
            ],
            'orderId' => $orderId,
            'currencyCode' => 'RUB',
            'externalOperationId' => $payment_reference,
            'orderAmount' => (double)$price,
            'redirectUrls' => [
                'onSuccess' => route('payment.set.status', ['payment_status' => 'success', 'order_id' => $orderId]),
                'onError' => route('payment.set.status', ['payment_status' => 'error', 'order_id' => $orderId]),
                'onAbort' => route('payment.set.status', ['payment_status' => 'error', 'order_id' => $orderId])
            ]
        ]);
        if ($response->successful()) {
            $data = $response->json();
            try {
                $paymentUrl = $data['data']['paymentUrl'];
                return $paymentUrl;
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Could not find paymentUrl in response'
                ], 500);
            }
        } else {
            $errorMessage = $response->body();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create Yandex Pay order: ' . $errorMessage
            ], 500);
        }
    }

    public function tinkoffPayCallback(Request $request)
    {
        $tinkoff = app(TinkoffService::class);
        
        // Verify the token
        $receivedToken = $request->input('Token');
        $calculatedToken = $tinkoff->generateToken($request->except(['Token']));
        
        if ($receivedToken !== $calculatedToken) {
            Log::error('Tinkoff callback token mismatch');
            return response()->json(['error' => 'Invalid token'], 400);
        }

        // Process the payment status
        $status = $request->input('Status');
        $orderId = $request->input('OrderId');
        
        // Update your order status accordingly
        // ... your order status update logic here ...

        return response()->json(['success' => true]);
    }

    public function initOrder(Request $request)
    {
        if ($request->input('order_type') == 'foundation') {
            return $this->processFoundationOrder($request);
        } else {
            return $this->processProjectSmetaOrder($request);
        }
    }

    public function initTinkoffPayment(Request $request)
    {
        try {
            $tinkoff = app(TinkoffService::class);
            //dd($request->all());
            $result = $tinkoff->initPayment([
                'amount' => $request->input('payment_amount'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'orderId' => $request->input('orderId'),
                'description' => $request->input('description'),
            ]);

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Tinkoff payment error: ' . $e->getMessage());
            return response()->json(['error' => 'Payment system error'], 500);
        }
    }

    public function yandexPayMembershipCallback($inn, $payment_amount)
    {
        $isSandbox = env('YANDEX_PAY_SANDBOX');
        if ($isSandbox) {
            $baseUrl = 'https://sandbox.pay.yandex.ru';
        } else {
            $baseUrl = 'https://pay.yandex.ru';
        }
        $orderId = $inn;
        $price = 1;
        $title = "Членство СТРОЙКА.com на 30 дней";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Api-Key cc08ce31-8375-439d-86f1-1201533f53e7'
        ])->post("{$baseUrl}/api/merchant/v1/orders", [
            'cart' => [
                'items' => [
                    [
                        'productId' => "201",
                        'quantity' => [
                            'count' => 1
                        ],
                        'title' => $title,
                        'unitPrice' => 1,
                        'total' => 1
                    ]
                ],
                'total' => [
                    'amount' => 1
                ]
            ],
            'orderId' => $orderId,
            'currencyCode' => 'RUB',
            'externalOperationId' => $payment_reference,
            'orderAmount' => (double)1,
            'redirectUrls' => [
                'onSuccess' => route('page_login'),
                'onError' => route('blog.index'),
                'onAbort' => route('blog.index')
            ]
        ]);
        if ($response->successful()) {
            $data = $response->json();
            try {
                $paymentUrl = $data['data']['paymentUrl'];
                return $paymentUrl;
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Could not find paymentUrl in response'
                ], 500);
            }
        } else {
            $errorMessage = $response->body();
            dd($errorMessage);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create Yandex Pay order: ' . $errorMessage
            ], 500);
        }
    }

    public function setPaymentStatus($payment_status, $order_id)
    {
        $project = Project::where('human_ref', base64_decode($order_id))->firstOrFail();
        $project->payment_status = $payment_status;
        if ($payment_status == 'success') {
            //$user = User::find($project->user_id);
            //$user->notify(new ReceiptNotification($project, $design, $user->email));
        }
        $project->is_example ? $project->payment_status = 'error' : $project->payment_status = $payment_status;
        $project->save();
        return redirect()->route('payment.status', ['payment_status' => $payment_status, 'order_id' => $order_id]);
    }
}
