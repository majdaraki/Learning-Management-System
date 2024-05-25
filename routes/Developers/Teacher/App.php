<?php
use App\Http\Controllers\Api\V1\Teacher\{
    CoursesController,
    CoursesVideoController
};

Route::prefix('teachers/')
    ->middleware(['auth:sanctum', 'teacher'])
    ->group(function () {
        Route::apiResource('courses', CoursesController::class);
        Route::apiResource('courses.videos', CoursesVideoController::class)->shallow();
    });
