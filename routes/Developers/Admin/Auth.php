<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\Auth\{
    LoginController,
    ForgetPassword,
    ResetPassword,
};


// Auth Routes
Route::prefix('admins/auth/')->group(function () {
    Route::post('login', [LoginController::class, 'create']);
    Route::get('logout', [LoginController::class, 'destroy'])->middleware('auth:sanctum');

    // Handle Forget Password Routes



    Route::post('forget-password', [ForgetPassword::class, 'forgetPassword']);
    Route::post('verify', [ResetPassword::class, 'verify']);
    Route::post('reset-password', [ResetPassword::class, 'resetPassword']);

});
