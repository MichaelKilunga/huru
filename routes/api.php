<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SmsController;

Route::post('/sms/inbound', [SmsController::class, 'inbound']);