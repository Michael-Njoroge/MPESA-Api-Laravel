<?php

namespace App\Http\Controllers\payments\mpesa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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
            return $responseData;
            // if (isset($responseData['access_token'])) {
            //     return $responseData['access_token'];
            // } else {
            //     return response()->json(['error' => 'Access token not found in response'], 500);
            // }
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
            "ConfirmationURL" =>env('MPESA_TEST_URL') . '/api/confirmation',
            "ValidationURL" => env('MPESA_TEST_URL') . '/api/validation'
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

    //STK Transaction 
    public function stkPush(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'phone' => 'required|regex:/^[0-9]{9,12}$/',
            'account' => 'required|string|max:255'
        ]);
        $timeStamp = now()->format('YmdHis');
        $password = env('MPESA_STK_SHORTCODE').env('MPESA_PASSKEY').$timeStamp;
        $hashedPassword = base64_encode($password);
        $body = array(
           "BusinessShortCode" =>  env('MPESA_STK_SHORTCODE'),
           "Password" => $hashedPassword,
           'Timestamp' =>  $timeStamp,
           "TransactionType" => "CustomerPayBillOnline",  
           "Amount" => $request->amount,
           "PartyA" => $this->formatPhoneNumber($request->phone),
           "PartyB" => env('MPESA_STK_SHORTCODE'),
           "PhoneNumber" => $this->formatPhoneNumber($request->phone),
           "CallBackURL" => env('MPESA_TEST_URL') . '/api/stkpush',
            "AccountReference" => $request->account,    
            "TransactionDesc" => $request->account
        );

        $url = env('MPESA_ENVIRONMENT') === '0' 
            ? 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest'
            : 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

        $response = $this->makeHttp($url, $body);
        if ($response->successful()) {
            Log::info('STK Push Response:', $response->json());
        } else {
            Log::error('STK Push Error:', $response->json());
        }
        return $response;
    }

    //Business To Customer (B2C) 
    public function b2cRequest(Request $request)
    {
        $originatorConversationID = (string) Str::uuid();
        $body = array(
           "OriginatorConversationID" =>  $originatorConversationID,
           "InitiatorName" =>  env('MPESA_B2C_INITIATOR'),
           "SecurityCredential" => env('MPESA_B2C_PASSWORD'),
           "CommandID" => "BusinessPayment",
           "Amount" => $request->amount,
           "PartyA" => env('MPESA_STK_SHORTCODE'),
           "PartyB" => $request->phone,
           "Remarks" => $request->remarks,
           "QueueTimeOutURL" => env('MPESA_TEST_URL') . '/api/b2cresult',
           "ResultURL" => env('MPESA_TEST_URL') . '/api/b2ctimeout',
           "Occassion" => $request->occassion
        );

        $url = env('MPESA_ENVIRONMENT') === '0' 
            ? 'https://sandbox.safaricom.co.ke/mpesa/b2c/v3/paymentrequest'
            : 'https://api.safaricom.co.ke/mpesa/b2c/v3/paymentrequest';

        $response = $this->makeHttp($url, $body);
        return $response;
    }

    //Transaction Status 
    public function transactionStatus(Request $request)
    {
        $originatorConversationID = (string) Str::uuid();
        $body = array(
           "Initiator" =>  env('MPESA_B2C_INITIATOR'),
           "SecurityCredential" => env('MPESA_B2C_PASSWORD'),
           "CommandID" => "TransactionStatusQuery",
           "TransactionID" => $request->transactionid,
           "OriginatorConversationID" => $originatorConversationID,
           "PartyA" => env('MPESA_STK_SHORTCODE'),
           "IdentifierType" => 4,
           "ResultURL" => env('MPESA_TEST_URL') . '/api/result_url',
           "QueueTimeOutURL" => env('MPESA_TEST_URL') . '/api/timeout_url',
           "Remarks" => "TransactionStatusQuery",
           "Occassion" => "TransactionStatusQuery"
        );

        $url = env('MPESA_ENVIRONMENT') === '0' 
            ? 'https://sandbox.safaricom.co.ke/mpesa/transactionstatus/v1/query'
            : 'https://api.safaricom.co.ke/mpesa/transactionstatus/v1/query';

        $response = $this->makeHttp($url, $body);
        return $response;
    }

    //C2B M-Pesa Reversal Transaction.
    public function reversalTransaction(Request $request)
    {
        $body = array(
           "Initiator" => env('MPESA_B2C_INITIATOR'),  
           "SecurityCredential" =>   env('MPESA_B2C_PASSWORD'),  
           "CommandID" => "TransactionReversal",    
           "TransactionID" =>  $request->transactionid,    
           "Amount" => $request->amount,   
           "ReceiverParty" => env('MPESA_STK_SHORTCODE'),  
           "RecieverIdentifierType" => 11,    
           "ResultURL" => env('MPESA_TEST_URL') . '/api/reverse-result-url',    
           "QueueTimeOutURL" => env('MPESA_TEST_URL') . '/api/reverse-timeout-url',
           "Remarks" => "TransactionReversal",    
           "Occasion" => "TransactionReversal"
        );

        $url = env('MPESA_ENVIRONMENT') === '0' 
            ? 'https://sandbox.safaricom.co.ke/mpesa/reversal/v1/request'
            : 'https://api.safaricom.co.ke/mpesa/reversal/v1/request';

        $response = $this->makeHttp($url, $body);
        return $response;
    }

    public function makeHttp($url, $body)
    {
        $getToken = $this->getAccessToken();
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $getToken['access_token'],
            'Content-Type' => 'application/json',
            'ngrok-skip-browser-warning' => 'skip-warning'
        ])->post($url, $body);

       if ($response->failed()) {
            Log::error('HTTP Request Failed:', [
                'url' => $url,
                'body' => $body,
                'response' => $response->json()
            ]);
        }

        return $response;
    }

    private function formatPhoneNumber($phone)
    {
        $phone = (substr($phone, 0, 1) == "+") ? str_replace("+", "", $phone) : $phone;
        $phone = (substr($phone, 0, 1) == "0") ? preg_replace("/^0/", "254", $phone) : $phone;
        $phone = (substr($phone, 0, 1) == "7") ? "254{$phone}" : $phone;
        return $phone;
    }
}
