<?php


use App\Http\Controllers\Api\V1\Student\Auth\{
    ForgetPassword,
    LoginController,
    RegisterController,
    ResetPassword,
};


// Auth Routes

Route::prefix('students/auth/')->group(function () {
Route::post('register', [RegisterController::class, 'create']);
Route::post('login', [LoginController::class, 'create']);
Route::get('logout', [LoginController::class, 'destroy'])->middleware('auth:sanctum');

// Handle Forget Password Routes

Route::post('forget-password',[ForgetPassword::class,'forgetPassword']);
Route::post('verify',[ResetPassword::class,'verify']);
Route::post('reset-password',[ResetPassword::class,'resetPassword']);


// verification email
Route::post('check-code',[RegisterController::class,'verify']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('resend-code',[RegisterController::class,'resend']);

});

});
