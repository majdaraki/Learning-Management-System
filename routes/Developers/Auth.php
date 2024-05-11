<?php


use App\Http\Controllers\Api\V1\Auth\{
    ForgetPasswordController,
    LoginController,
    RegisterController,
    ResetPasswordController,
};


// Auth Routes
Route::post('/register', [RegisterController::class, 'create']);
Route::post('/login', [LoginController::class, 'create']);
Route::get('/logout', [LoginController::class, 'destroy'])->middleware('auth:sanctum');

// Handle Forget Password Routes
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.reset');
Route::post('/forgot-password', [ForgetPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
