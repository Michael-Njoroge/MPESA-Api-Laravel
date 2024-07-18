<?php

namespace App\Http\Controllers\payments\mpesa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class MpesaResponsesController extends Controller
{
    public function validation(Request $request)
    {
        Log::info("Validation endpoint hit");
        Log::info($request->all());
        return [
            'ResultCode' => 0,
            'ResultDesc' => 'Accept Service',
            'ThirdPartyTransID' => rand(3000,10000)
        ];
    }

    public function confirmation(Request $request)
    {
        Log::info("Confirmation endpoint hit");
        Log::info($request->all());
    }
}
