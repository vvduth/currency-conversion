<?php

use App\Http\Controllers\CurrencyConverterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', ['name' => 'Laravel1']);
});

