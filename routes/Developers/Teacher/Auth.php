<?php


use App\Http\Controllers\Api\V1\Teacher\Auth\{
    ForgetPassword,
    LoginController,
    ResetPassword,
};


// Auth Routes

Route::prefix('Teacher/')->group(function () {
Route::post('/login', [LoginController::class, 'create']);
Route::get('/logout', [LoginController::class, 'destroy'])->middleware('auth:sanctum');


// Handle Forget Password Routes

Route::post('forget-password',[ForgetPassword::class,'forgetPassword']);
Route::post('verify',[ResetPassword::class,'verify']);
Route::post('reset-password',[ResetPassword::class,'resetPassword']);
});
