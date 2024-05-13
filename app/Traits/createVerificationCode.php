<?php

namespace App\Traits;
use App\Models\{
    User,
    Code
};
use Carbon\Carbon;
trait createVerificationCode
{

    protected function getOrCreateVerificationCode($email,$type)
    {
        $currentCode = Code::where('email', $email)
                           ->first();

        if ($currentCode && $currentCode->created_at > now()->subMinutes(5)) {
            $currentCode->expires_at = Carbon::now()->addMinutes(5);
            $currentCode->save();
            return $currentCode->verification_code;
        }

        $verificationCode = mt_rand(100000, 999999);
        Code::create([
            'email' => $email,
            'verification_code' => $verificationCode,
            'type'=>$type,
            'expires_at' => Carbon::now()->addMinutes(5),

        ]);

        return $verificationCode;
    }

}
