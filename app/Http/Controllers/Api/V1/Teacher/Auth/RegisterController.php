<?php

namespace App\Http\Controllers\Api\V1\Teacher\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Models\{
    User,
    Code
};
use App\Traits\{
    VerifyCodeForRegister,
    ExpierCode,
    createVerificationCode,
    Media
};
use Illuminate\Support\Facades\Notification;
use App\Notifications\verfication_code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{

    use VerifyCodeForRegister,ExpierCode,createVerificationCode,Media;


    public function create(RegisterRequest $request) {
        return DB::transaction(function () use ($request){

            $teacher = User::create($request->all());
            Auth::login($teacher);
            $verificationCode = $this->getOrCreateVerificationCode($teacher->email, 'cheack-email');

            if ($request->hasFile('image')) {
                $request_image = $request->file('image');
                $image_name = $this->setMediaName([$request_image])[0];

                $teacher->image()->create(['name' => $image_name]);
                $this->saveMedia([$request_image], [$image_name], 'public/User');
            }

            Notification::route('mail',$teacher->email)
                ->notify(new verfication_code($teacher, $verificationCode));

            $token = $teacher->createToken('access_token')->plainTextToken;

            return response()->json([
                'message' => 'Code has been sent',
                'teacher'=>$teacher,
                'access_token' => $token

            ],200);
        });
    }


    public function resend(Request $request) {
        return DB::transaction(function () use ($request) {
            $teacher=Auth::user();
            $verificationCode = $this->getOrCreateVerificationCode($teacher->email, 'check-email');
            Notification::route('mail', $teacher->email)
                ->notify(new verfication_code($teacher, $verificationCode));
            return $this->sudResponse('Code has been resent');
        });
    }


    public function verify(Request $request){
        $request->validate([
            'verification_code'=>'required'
        ]);
        return $this->verifyCode($request['verification_code']);

    }

}
