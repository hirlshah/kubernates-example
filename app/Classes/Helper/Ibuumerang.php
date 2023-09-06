<?php

namespace App\Classes\Helper;

use Illuminate\Support\Facades\Log;
use Auth;

class Ibuumerang
{
    public static function checkSubscription() {
        $res = false;
        if(Auth::check()){ 
        
            $ch = curl_init();
            $url = env('IBUUMERANG_SUBSCRIPTION_URL');
            $headers = array(
                "content-type: application/json",
                "x-company-code: ".env('IBUUMERANG_SUBSCRIPTION_COMPANY_CODE')."",
                "x-client-id: ".env('IBUUMERANG_SUBSCRIPTION_CLIENT_ID')."",
                "x-client-secret: ".env('IBUUMERANG_SUBSCRIPTION_CLIENT_SECRET').""
            );
            $data = array(
                "email" => Auth::user()->email
            );
            
            $options = array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_POSTFIELDS => json_encode($data)
            );
            
            curl_setopt_array($ch, $options);
            
            $response = curl_exec($ch);
            $response = json_decode($response, true);
            $error = curl_error($ch);
            
            curl_close($ch);
            
            if ($error) {
                $res = false;
            } else {
                if(isset($response['response'])&& !empty($response['response'])) {
                    if(isset($response['response']['inService']) && $response['response']['inService'] == true) {
                        $res = true;
                    }
                }
            }
        }
        return $res;
    }
}