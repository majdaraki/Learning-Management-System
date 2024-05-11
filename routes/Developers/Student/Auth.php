<?php


use App\Http\Controllers\Api\V1\Student\Auth\{
    ForgetPassword,
    LoginController,
    RegisterController,
    ResetPassword,
};


// Auth Routes

Route::prefix('Student/')->group(function () {
Route::post('/register', [RegisterController::class, 'create']);
Route::post('/login', [LoginController::class, 'create']);
Route::get('/logout', [LoginController::class, 'destroy'])->middleware('auth:sanctum');

// Handle Forget Password Routes

Route::post('forget-password',[ForgetPassword::class,'forgetPassword']);
Route::post('verify',[ResetPassword::class,'verifyCode']);
Route::post('reset-password',[ResetPassword::class,'resetPassword']);
});
