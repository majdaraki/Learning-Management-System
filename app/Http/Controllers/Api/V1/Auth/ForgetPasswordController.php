<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgetPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $response = Password::sendResetLink(
            $request->only('email')
        );
        // dd(Password::RESET_LINK_SENT);

        if ($response === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Reset password email sent.']);
        } else {
            return response()->json(['message' => 'Unable to send reset password email.'], 500);
        }
    }
}
