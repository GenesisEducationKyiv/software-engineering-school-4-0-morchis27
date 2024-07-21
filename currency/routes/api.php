<?php

use App\Http\Controllers\CurrencyExchangeRateController;
use Illuminate\Support\Facades\Route;
//dd(\Illuminate\Support\Facades\Request::capture());
Route::get('/rate', [CurrencyExchangeRateController::class, 'getExchangeRate']);
