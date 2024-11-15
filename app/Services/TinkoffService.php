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
        $this->apiUrl = "https://securepay.tinkoff.ru/v2/Init";
    }

    public function generateToken(array $data): string
    {
        // Create a new array with only root-level parameters
        $tokenData = [
            'Amount' => $data['Amount'],
            'OrderId' => $data['OrderId'],
            'Description' => $data['Description'],
        ];
        
        // Sort by key
        ksort($tokenData);
        
        // Concatenate only values
        $plainString = '';
        foreach ($tokenData as $value) {
            $plainString .= $value;
        }
        $plainString .= $this->secretKey . $this->terminalKey;
        //dd($tokenData, $plainString, hash('sha256', $plainString));
        // Generate SHA-256 hash
        return hash('sha256', $plainString);
    }

    public function initPayment(array $params)
    {
        if (env('TEST_ENVIRONMENT') == true) {
            $params['amount'] = 1000;
        }
        $data = [
            'TerminalKey' => $this->terminalKey,
            'Amount' => $params['amount'],
            'OrderId' => $params['orderId'],
            'Description' => $params['description'],
            'DATA' => [
                'Email' => $params['email'],
                'Phone' => $params['phone'],
                "TinkoffPayWeb" => "true",
                "Device" => "Desktop",
            ],
            'Receipt' => [
                'Email' => $params['email'],
                'Phone' => $params['phone'],
                'Taxation' => "osn",
                'Items' => [
                    [
                        "Name" => $params['description'],
                        "Price" => $params['amount'],
                        "Quantity" => 1,
                        "Amount" => $params['amount'],
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
            ])->post($this->apiUrl, $data);
            dd($response->body());
            if (!$response->successful()) {
                //Log::error('Tinkoff API error:', $response->json());
                throw new \Exception('Tinkoff API error: ' . $response->body());
            }

            $responseData = json_decode($response->body(), true) ?? null;
            $url = $responseData['PaymentURL'] ?? null;
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