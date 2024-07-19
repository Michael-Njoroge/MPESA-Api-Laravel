<?php

namespace App\Http\Controllers\payments\mpesa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MpesaController extends Controller
{
    //Get AccessToken
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

    //Register URL 
    public function registerURLs()
    {
        $body = array(
            "ShortCode" => env('MPESA_SHORTCODE'),
            "ResponseType" => "Completed",
            "ConfirmationURL" =>env('MPESA_TEST_URL') . '/Mpesa-STK/api/confirmation',
            "ValidationURL" => env('MPESA_TEST_URL') . '/Mpesa-STK/api/validation'
        );

        $url = env('MPESA_ENVIRONMENT') === '0' 
            ? 'https://sandbox.safaricom.co.ke/mpesa/c2b/v2/registerurl'
            : 'https://api.safaricom.co.ke/mpesa/c2b/v2/registerurl';

        $response = $this->makeHttp($url, $body);
        return $response;
    }

    //Simulate Transaction
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

    //Business To Customer (B2C) 
    public function b2cRequest(Request $request)
    {
        $body = array(
            "OriginatorConversationID" =>  "feb5e3f2-fbbc-4745-844c-ee37b546f627",
           "InitiatorName" =>  env('MPESA_B2C_INITIATOR'),
           "SecurityCredential" => env('MPESA_B2C_PASSWORD'),
           "CommandID" => "BusinessPayment",
           "Amount" => $request->amount,
           "PartyA" => env('MPESA_SHORTCODE'),
           "PartyB" => $request->phone,
           "Remarks" => $request->remarks,
           "QueueTimeOutURL" => env('MPESA_TEST_URL') . '/Mpesa-STK/api/b2cresult',
           "ResultURL" => env('MPESA_TEST_URL') . '/Mpesa-STK/api/b2ctimeout',
           "Occassion" => $request->occassion
        );

        $url = env('MPESA_ENVIRONMENT') === '0' 
            ? 'https://sandbox.safaricom.co.ke/mpesa/b2c/v3/paymentrequest'
            : 'https://api.safaricom.co.ke/mpesa/b2c/v3/paymentrequest';

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
