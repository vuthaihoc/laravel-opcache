<?php

Route::get(
    config('opcache.enpoint'),
    \HocVT\LaravelOpcache\Http\Controllers\OpcacheController::class
)->name('laravel.opcache.control');
