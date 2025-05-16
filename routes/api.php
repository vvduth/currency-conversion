<?php

use App\Http\Controllers\CurrencyConverterController;
use Illuminate\Support\Facades\Route;



//Route::post('/convert', [CurrencyConverterController::class, 'convert']);
Route::post('/convert', [CurrencyConverterController::class, 'convert']);
