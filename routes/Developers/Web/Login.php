<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\LoginController;



Route::prefix('web/auth/')->group(function () {
    Route::post('login', [LoginController::class, 'create']);
    Route::get('logout', [LoginController::class, 'destroy'])->middleware('auth:sanctum');
    });
