<?php
use App\Http\Controllers\Api\V1\Teacher\{
    CoursesController
};

Route::prefix('teachers/')
    ->middleware(['auth:sanctum', 'teacher'])
    ->group(function () {
        Route::apiResource('teachers',CoursesController::class);
    });
