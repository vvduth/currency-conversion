<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', ['name' => 'Laravel1']);
});

Route::get('/convert', function () {
    return 'Welcome to the conversion page!';
});
