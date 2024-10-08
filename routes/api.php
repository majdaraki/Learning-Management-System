<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

include __DIR__ . '/Developers/Admin/Auth.php';
include __DIR__ . '/Developers/Admin/App.php';

include __DIR__ . '/Developers/Student/Auth.php';
include __DIR__ . '/Developers/Student/App.php';


include __DIR__ . '/Developers/Teacher/Auth.php';
include __DIR__ . '/Developers/Teacher/App.php';


include __DIR__ . '/Developers/Web/Login.php';
include __DIR__ . '/Developers/Web/App.php';
