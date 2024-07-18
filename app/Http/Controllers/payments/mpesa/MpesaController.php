<?php

namespace App\Http\Controllers\payments\mpesa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MpesaController extends Controller
{
    // public function getAccessToken()
    // {
        // $url = env('MPESA_ENVIRONMENT') === 0 ? "" : "";
        // $curl = curl_init($url);
        // curl_setopt_array(
        //     $curl,
        //     array(
        //         CURLOPT_HTTPHEADER => ['Content-Type: application/json; charset=utf8'],
        //         CURLOPT_RETURNTRANSFER => true,
        //         CURLOPT_HEADER => FALSE,
        //         CURLOPT_USERPWD => env('MPESA_CONSUMER_KEY') . ':' . env('MPESA_CONSUMER_SECRET')
        //     )
        // );
        // $response = json_decode(curl_exec($curl))
        // curl_close($curl);
        // return $response;
    // }

    public function getAccessToken()
    {

         $url = env('MPESA_ENVIRONMENT') === '0' 
            ? 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'
            : 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $response = Http::withHeaders([
            'Content-Type' => 'application/json; charset=utf8',
        ])->withBasicAuth(env('MPESA_CONSUMER_KEY'), env('MPESA_CONSUMER_SECRET'))
          ->get($url);

        if ($response->successful()) {
            return $response->json();
        } else {
            return response()->json(['error' => 'Failed to get access token'], $response->status());
        }
    }
}
