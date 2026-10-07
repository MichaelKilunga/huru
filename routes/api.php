<?php

use App\Http\Controllers\SmsController;
use Illuminate\Support\Facades\Route;

// Africa's Talking callbacks. Register them with ?token=<AT_WEBHOOK_SECRET>.
Route::middleware('sms.webhook')->group(function () {
    Route::post('/sms/inbound', [SmsController::class, 'inbound'])->name('sms.inbound');
    Route::post('/sms/delivery', [SmsController::class, 'deliveryReport'])->name('sms.delivery');
});
