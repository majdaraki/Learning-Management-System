<?php

namespace App\Traits;
use App\Models\{
    User,
    Code
};

use App\Traits\{
    Responses,
    ExpierCode

};
use Carbon\Carbon;
trait verifyCode
{

    protected function verifyCode($code)
    {

        $code = Code::where('verification_code', $code)
                    ->first();

        if (!$code) {
            return $this->sudResponse('Code not correct !', 400);
        }

        if ($this->isCodeExpired($code)) {
            $code->delete();
            return $this->sudResponse('Code has expired', 400);
        }
        $user = User::where('email', $code->email)->first();
        $user->email_verified_at = Carbon::now();
        $user->save();
        $code->delete();
        return $this->sudResponse('Code has been confirmed');
    }
}
