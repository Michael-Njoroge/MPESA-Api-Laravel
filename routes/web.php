<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\payments\mpesa\MpesaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/b2c', function () {
    return view('b2c');
});

Route::get('/stk', function () {
    return view('stk');
});

Route::get('/transaction-status', function () {
    return view('status');
});

Route::get('/transaction-reversal', function () {
    return view('reversal');
});

Route::get('/get-access-token', [MpesaController::class, 'getAccessToken']);
Route::post('/register-urls', [MpesaController::class, 'registerURLs']);
Route::post('/simulate-transaction', [MpesaController::class, 'simulateTransaction']);
Route::post('/b2c-simulate', [MpesaController::class, 'b2cRequest']);
Route::post('/stk-simulate', [MpesaController::class, 'stkPush']);
Route::post('/transaction-status', [MpesaController::class, 'transactionStatus']);
Route::post('/transaction-reversal', [MpesaController::class, 'reversalTransaction']);

