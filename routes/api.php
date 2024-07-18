<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\payments\mpesa\MpesaResponsesController;

Route::post('validation',[MpesaResponsesController::class, 'validation']);
Route::post('confirmation',[MpesaResponsesController::class, 'confirmation']);
