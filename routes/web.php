<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\payments\mpesa\MpesaController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/get-access-token', [MpesaController::class, 'getAccessToken']);
Route::post('/register-urls', [MpesaController::class, 'registerURLs']);
Route::post('/simulate-transaction', [MpesaController::class, 'simulateTransaction']);

