<?php

namespace App;

use MoveMoveIo\DaData\Facades\DaDataPhone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
/**
 * Class DaData
 * @package App\DaData
 */
class DaData
{
    private $badWords = ['пипец', 'пидор', 'хуй'];
    private $types_of_values = ['phone', 'address', 'email'];
    
    
    public function lookForErrors($type_of_request, Request $request) {
        $errors = [];
        if ($type_of_request == 'phone') {
            $errors = $this->clPhone($request->phone);
        }
        return $errors;
    }

    public function censorWords($text, $user = 555) {
        
        foreach ($badWords as $badWord) {
            if (strpos($text, $badWord) !== false) {
                $text = preg_replace('/[a-zA-Z]/', '*', $text);
                return $text;
            } else {
                
            }
        }
        return $text;
    }
    
    /**
    * DaData phone exmaple
    *
    * @return void
    */
    public function clPhone($phone)
    {
        $phone = $this->sanitizePhone($phone);
        try {
            return DaDataPhone::standardization($phone);
        } catch (\Exception $e) {
            Log::error("can't clean phone number: $phone", ['exception' => $e]);
            return $phone;
        }
    }

    public function sanitizePhone($phone) {
        return preg_replace('/[^0-9]/', '', $phone);
    }

    public function cleanAddress($address) {
        return DaDataAddress::standardization($address);
    }



}