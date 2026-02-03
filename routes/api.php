<?php

use App\Http\Controllers\CompanyApiController;
use App\Http\Middleware\JWTMiddleware; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware([JWTMiddleware::class])->prefix('company')->controller(CompanyApiController::class)->group(function () {
    
    Route::get('', 'index');     
    Route::post('', 'store');         
    Route::get('{id}', 'show');       
    Route::put('{id}', 'update');     
    Route::delete('{id}', 'destroy'); 

});