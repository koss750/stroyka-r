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

class OrderController extends Controller
{
    public function processFoundationOrder(Request $request)
    {
        return true;
        // First, generate the Excel file
        $templateController = new TemplateController();
        $excelResponse = $templateController->generateExcel($request);

        // Check if there was an error generating the Excel file
        if ($excelResponse->status() !== 200) {
            return $excelResponse;
        }

        $excelData = json_decode($excelResponse->getContent(), true);
        $excelUrl = $excelData['download_url'];

        // Load the generated Excel file
        $spreadsheet = IOFactory::load(storage_path('app/public/' . basename($excelUrl)));

        // Generate the foundation smeta
        $smetaUrl = $templateController->generateFoundationSmeta($spreadsheet);

        // Return both URLs
        return response()->json([
            'excel_url' => $excelUrl,
            'smeta_url' => $smetaUrl
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
        //$user->notify(new ReceiptNotification($project, $design, $user->email));
        $urlcontent = base64_encode($paymentUrl);
        return response()->json([
            'success' => true,
            'paymentUrl' => $paymentUrl,
            'urlcontent' => $urlcontent
        ]);
    }

    public function processMembershipOrder(Request $request)
    {
        //check if user already registered as a normal user
        $user = User::where('email', $request->input('email'))->first();
        if ($user) {
            return response()->json(['error' => 'User already registered'], 401);
        } else {
            //create user
            $registerController = new RegisterController();
            $user = $registerController->create($request, true);
            $request->merge(['user_id' => $user->id]);
        }


        /*
        payment_provider: 'yandex',
                    payment_amount: 1,
                    order_type: 'membership',
                    inn: document.getElementById('inn').value,
                    company_name: document.getElementById('company_name').value,
                    name: document.getElementById('contact_name').value,
                    email: document.getElementById('email').value,
                    phone: document.getElementById('phone').value,
                    additional_phone: document.getElementById('additional_phone').value,
                    password: document.getElementById('password').value,
                    legal_address: document.getElementById('legal_address').value,
                    physical_address: document.getElementById('physical_address').value,
                    main_region: mainRegionSelect.value,
                    region_codes: regionCodesInput.value,
                    offer_id: offer_id,
        */
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

    public function initOrder(Request $request, $provider = 'default')
    {
        if ($provider === 'default') {
            $provider = env('PAYMENT_PROVIDER');
        } elseif ($provider === 'free') {
            return true;
        }
        $response = $this->processProjectSmetaOrder($request);
        switch ($provider) {
            case 'TINKOFF-SB':
                try {
                    return $this->initTinkoffPayment($request);
                } catch (\Exception $e) {
                    Log::error('Tinkoff payment error: ' . $e->getMessage());
                        return response()->json(['error' => 'Payment system error'], 500);
                    }
                    break;
                default:
                    return response()->json(['error' => 'Invalid payment provider'], 400);
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
        $project->is_example ? $project->payment_status = 'error' : $project->payment_status = $payment_status;
        $project->save();
        return redirect()->route('payment.status', ['payment_status' => $payment_status, 'order_id' => $order_id]);
    }
}
