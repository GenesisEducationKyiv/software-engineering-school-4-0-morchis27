<?php

use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);
Route::get('email/verify/{subscriber}', [SubscriptionController::class, 'verify'])
    ->name('verification.verify')
    ->middleware('signed');
