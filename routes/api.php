<?php

use App\Http\Controllers\CompanyApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(CompanyApiController::class)->prefix('company')->group(function () {
    Route::get('', 'index');
});
