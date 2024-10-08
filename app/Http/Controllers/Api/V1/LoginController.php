<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create(LoginRequest $request) {
        $credentials = $request->only('email', 'password');

        if (! Auth::attempt($credentials)) {

            return response()->json(['message' => 'your provided credentials cannot be verified.'], 401);
        }
        $teacher = Auth::user();

        $token = $teacher->createToken('access_token')->plainTextToken;

        $roleName = $teacher->getRoleNames()[0];

        return response()->json([
            'message' => 'logged in successfully.',
            'Teacher' => [
                'id' => $teacher->id,
                'first_name' => $teacher->first_name,
                'last_name' => $teacher->last_name,
                'email' => $teacher->email,
                'status' => $teacher->status,
                'created_from' => $teacher->created_from,
                'image' => $teacher->image,
                'role' =>$roleName,
            ],
            'access_token' => $token,
        ]);
    }

    public function destroy() {
        Auth::user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }
}