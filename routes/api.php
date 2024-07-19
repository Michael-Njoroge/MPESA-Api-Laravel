<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\payments\mpesa\MpesaResponsesController;
use App\Http\Controllers\payments\mpesa\MpesaController;

Route::post('validation',[MpesaResponsesController::class, 'validation']);
Route::post('confirmation',[MpesaResponsesController::class, 'confirmation']);
Route::post('b2cresult',[MpesaResponsesController::class, 'b2cCallback']);
Route::post('b2ctimeout',[MpesaResponsesController::class, 'b2cTimeout']);
Route::post('stkpush',[MpesaResponsesController::class, 'stkPush']);

// Route::post('/get-access-token', [MpesaController::class, 'getAccessToken']);
// Route::post('/register-urls', [MpesaController::class, 'registerURLs']);
// Route::post('/simulate-transaction', [MpesaController::class, 'simulateTransaction']);
// Route::post('/b2c-simulate', [MpesaController::class, 'b2cRequest']);
// Route::post('/stk-simulate', [MpesaController::class, 'stkPush']);

