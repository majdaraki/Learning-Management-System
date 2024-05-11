<?php

namespace App\Http\Controllers\Api\V1\Admin\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{

    Code,
    User
};

use App\Traits\{
    Responses,
    ExpierCode

};
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    Auth,
    DB
};
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\verfication_code;

class ResetPassword extends Controller{

    use ExpierCode,Responses;
    public function verifyCode(Request $request)
    {
        $request->validate([
            'verification_code' => 'required',
        ]);

        $code = Code::where('verification_code', $request->verification_code)
                    ->first();

        if (!$code) {
            return $this->sudResponse('Code not correct !', 400);
        }

        if ($this->isCodeExpired($code)) {
            $code->delete();
            return $this->sudResponse('Code has expired', 400);
        }

        return $this->sudResponse('Code has been confirmed');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $admin = User::where('email', $request['email'])->get()->first();

        $admin->password = bcrypt($request->password);
        $admin->save();

        Code::where('email', $request->email)->delete();

        return $this->sudResponse('Your password has been reset !');
    }



}
