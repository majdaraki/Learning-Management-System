<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\Teacher\Auth\{
    ForgetPassword,
    RegisterController,
    LoginController,
    ResetPassword
};


// Auth Routes

Route::prefix('teachers/auth/')->group(function () {
Route::post('login', [LoginController::class, 'create']);
Route::get('logout', [LoginController::class, 'destroy'])->middleware('auth:sanctum');

Route::post('register', [RegisterController::class, 'create']);
Route::post('check-code',[RegisterController::class,'verify']);
// Handle Forget Password Routes

Route::post('forget-password',[ForgetPassword::class,'forgetPassword']);
Route::post('verify',[ResetPassword::class,'verify']);
Route::post('reset-password',[ResetPassword::class,'resetPassword']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('resend-code',[RegisterController::class,'resend']);

});
});
