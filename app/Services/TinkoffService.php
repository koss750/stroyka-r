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
        $this->terminalKey = config('services.tinkoff.terminal_key');
        $this->secretKey = config('services.tinkoff.secret_key');
        $this->apiUrl = "https://rest-api-test.tinkoff.ru/v2/Init";
    }

    public function generateToken(array $data): string
    {
        // Create a new array with only root-level parameters
        $tokenData = [
            'TerminalKey' => $data['TerminalKey'],
            'Amount' => $data['Amount'],
            'OrderId' => $data['OrderId'],
            'Description' => $data['Description'],
            'Password' => $this->secretKey
        ];
        
        // Sort by key
        ksort($tokenData);
        
        // Concatenate only values
        $plainString = '';
        foreach ($tokenData as $value) {
            $plainString .= $value;
        }
        
        // Generate SHA-256 hash
        return hash('sha256', $plainString);
    }

    public function initPayment(array $params)
    {
        $data = [
            'TerminalKey' => $this->terminalKey,
            'Amount' => $params['amount'] * 100,
            'OrderId' => $params['orderId'],
            'Description' => $params['description'],
            'DATA' => [
                'Email' => $params['email'],
                'Phone' => $params['phone'],
            ],
            'Receipt' => [
                'Email' => $params['email'],
                'Phone' => $params['phone'],
                'Taxation' => "osn",
                'Items' => [
                    [
                        "Name" => "Наименование товара 1",
                        "Price" => $params['amount'] ,
                        "Quantity" => 1,
                        "Amount" => $params['amount'],
                        "Tax" => "vat0",
                        "Ean13" => "303130323930303030630333435"
                    ]
                ]
            ]
        ];

        $data['Token'] = $this->generateToken($data);

        try {
            
            $response = Http::post($this->apiUrl, $data);
            dd($response->json());
            if (!$response->successful()) {
                //Log::error('Tinkoff API error:', $response->json());
                throw new \Exception('Tinkoff API error: ' . $response->body());
            }

            $responseData = $response->json();
            
            
            // Return both the payment URL from Tinkoff's response and our request data
            return [
                'paymentUrl' => $responseData['PaymentURL'] ?? $response->body(),
                'paymentData' => $data
            ];
        } catch (\Exception $e) {
            Log::error('Tinkoff payment init error: ' . $e->getMessage());
            throw $e;
        }
    }
}