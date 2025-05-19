<?php

use App\Http\Controllers\CurrencyConverterController;
use Illuminate\Support\Facades\Route;
use Spatie\Csp\AddCspHeaders;

Route::get('/', function () {
    return view('welcome', ['name' => 'Laravel1']);
})->middleware(AddCspHeaders::class);

Route::get('/token', function () {
    return csrf_token();
})->middleware(AddCspHeaders::class);


Route::get('/test', function () {
    return response('Test CSP');
});
