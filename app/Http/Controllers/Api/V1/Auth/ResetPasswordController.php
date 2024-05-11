<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    public function resetPassword(Request $request)
    {
        $credentials = $request->only(
            'email',
            'password',
            'password_confirmation',
            'token'
        );
        // dd($credentials);
        $response = Password::reset($credentials, function (User $user, string $password) {
            $user->forceFill([
                'password' => bcrypt($password),
                'remember_token' => \Str::random(60),
            ])->save();
        });
        if ($response === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password reset successfully.']);
        } else {
            return response()->json(['message' => 'Unable to reset password.'], 500);
        }
    }

}
