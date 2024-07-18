<?php

namespace App\Http\Controllers\payments\mpesa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MpesaController extends Controller
{
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
            $responseData = $response->json();
            if (isset($responseData['access_token'])) {
                return $responseData['access_token'];
            } else {
                return response()->json(['error' => 'Access token not found in response'], 500);
            }
        } else {
            return response()->json(['error' => 'Failed to get access token'], $response->status());
        }
    }

    //Customer To Business Register URL 
    public function registerURLs()
    {
        $body = array(
            "ShortCode" => env('MPESA_SHORTCODE'),
            "ResponseType" => "Completed",
            "ConfirmationURL" => 'https://7af8-154-159-237-127.ngrok-free.app/api/confirmation',
            "ValidationURL" => 'https://7af8-154-159-237-127.ngrok-free.app/api/validation'
        );

        $url = env('MPESA_ENVIRONMENT') === '0' 
            ? 'https://sandbox.safaricom.co.ke/mpesa/c2b/v2/registerurl'
            : 'https://api.safaricom.co.ke/mpesa/c2b/v2/registerurl';

        $response = $this->makeHttp($url, $body);
        return $response;
    }

    //Customer To Business Simulate Transaction
    public function simulateTransaction(Request $request)
    {
        $body = array(
            "CommandID" => "CustomerBuyGoodsOnline",
            "ShortCode" => env('MPESA_SHORTCODE'),
            "Amount" => $request->amount,
            "Msisdn" => env('MPESA_TEST_MSISDN'),
            "BillRefNumber" => $request->account
        );

        $url = env('MPESA_ENVIRONMENT') === '0' 
            ? 'https://sandbox.safaricom.co.ke/mpesa/c2b/v2/simulate'
            : 'https://api.safaricom.co.ke/mpesa/c2b/v2/simulate';

        $response = $this->makeHttp($url, $body);
        return $response;
    }

    public function makeHttp($url, $body)
    {
        $accessToken = $this->getAccessToken();
        // dd($accessToken);
       $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
            'ngrok-skip-browser-warning' => 'skip-warning'
        ])->post($url, $body);

        return $response;
    }
}
