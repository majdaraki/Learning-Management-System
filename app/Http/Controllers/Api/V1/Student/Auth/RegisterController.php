<?php

namespace App\Http\Controllers\Api\V1\Student\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function create(RegisterRequest $request) {
        return DB::transaction(function () use ($request){

            $student = User::create($request->all());
            Auth::login($student);

            $token = $student->createToken('access_token')->plainTextToken;

            return response()->json([
                'message' => 'User created Successfully.',
                'Student'=>$student,
                'access_token' => $token
            ],201);
        });
    }
}
