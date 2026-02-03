<?php

use App\Http\Controllers\CompanyApiController;
use App\Http\Middleware\JWTMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(JWTMiddleware::class)->prefix('v1')->group(function () {

    Route::controller(CompanyApiController::class)->prefix('company')->group(function () {
        Route::get('', 'index');
        Route::get('{id}', 'show');
        Route::post('', 'store');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
    });
});
