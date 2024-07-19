<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\payments\mpesa\MpesaResponsesController;

Route::post('validation',[MpesaResponsesController::class, 'validation']);
Route::post('confirmation',[MpesaResponsesController::class, 'confirmation']);
Route::post('b2cresult',[MpesaResponsesController::class, 'b2cCallback']);
Route::post('b2ctimeout',[MpesaResponsesController::class, 'b2cTimeout']);
