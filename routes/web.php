<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\payments\mpesa\MpesaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/b2c', function () {
    return view('b2c');
});

Route::post('/get-access-token', [MpesaController::class, 'getAccessToken']);
Route::post('/register-urls', [MpesaController::class, 'registerURLs']);
Route::post('/simulate-transaction', [MpesaController::class, 'simulateTransaction']);
Route::post('/b2c-simulate', [MpesaController::class, 'b2cRequest']);

