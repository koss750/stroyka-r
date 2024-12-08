<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class TinkoffService
{
    private $terminalKey;
    private $secretKey;
    private $apiUrl;

    public function __construct()
    {
        $this->terminalKey = env('TINKOFF_TERMINAL_KEY');
        $this->secretKey = env('TINKOFF_SECRET_KEY');
        $this->apiUrl = env('TINKOFF_API_URL');
    }

    public function generateToken(array $data): string
    {
        // Create a new array with only root-level parameters
        $tokenData = [
            'Amount' => $data['Amount'],
            'OrderId' => $data['OrderId'],
            'Description' => $data['Description'],
            'FailURL' => $data['FailURL'],
        ];
        
        // Sort by key
        ksort($tokenData);
        
        // Concatenate only values
        $plainString = '';
        foreach ($tokenData as $value) {
            $plainString .= $value;
        }
        $plainString .= $this->secretKey . $data['SuccessURL'] . $this->terminalKey;
        //dd($tokenData, $plainString, hash('sha256', $plainString));
        // Generate SHA-256 hash
        return hash('sha256', $plainString);
    }

    public function initPayment(array $params)
    {
        $base64ref = base64_encode($params['orderId']);
        //get device from user agent
        $device = strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false ? 'Mobile' : 'Desktop';
        if (strtoupper(substr($params['orderId'], 0, 1)) === 'M') {
            $successUrl = route('registration.success');
            $failUrl = route('registration.fail');
        } else {
            $successUrl = route('payment.set.status', ['payment_status' => 'success', 'order_id' => "$base64ref"]);
            $failUrl = route('payment.set.status', ['payment_status' => 'error', 'order_id' => "$base64ref"]);
        }
        $amount = $params['amount'];
        $data = [
            'TerminalKey' => $this->terminalKey,
            'Amount' => $amount,
            'OrderId' => $params['orderId'],
            'Description' => $params['description'],
            'SuccessURL' => $successUrl,
            'FailURL' => $failUrl,
            'DATA' => [
                'Email' => $params['email'],
                'Phone' => $params['phone'],
                "TinkoffPayWeb" => "false",
                "SberPayWeb" => "false",
                "Device" => $device,
            ],
            'Receipt' => [
                'Email' => $params['email'],
                'Phone' => $params['phone'],
                'Taxation' => "osn",
                'Items' => [
                    [
                        "Name" => $params['description'],
                        "Price" => $amount,
                        "Quantity" => 1,
                        "Amount" => $amount,
                        "Tax" => "vat10"
                    ]
                ]
            ]
        ];
        
        $data['Token'] = $this->generateToken($data);
        try {
            // Modify the request to ensure proper encoding
            $response = Http::withHeaders([
                'Content-Type' => 'application/json;charset=UTF-8'
            ])->post($this->apiUrl . '/Init', $data);
            if (!$response->successful()) {
                throw new \Exception('Tinkoff API error: ' . $response->body());
            }
            $responseData = json_decode($response->body(), true) ?? null;
            $url = $responseData['PaymentURL'] ?? null;
            //dd($responseData);
            $payment_id = $responseData['PaymentId'] ?? null;
            // Return both the payment URL from Tinkoff's response and our request data
            return [
                'paymentUrl' => $url,
                'payment_id' => $payment_id,
                'paymentData' => $data
            ];
        } catch (\Exception $e) {
            Log::error('Tinkoff payment init error: ' . $e->getMessage());
            throw $e;
        }
    }
}