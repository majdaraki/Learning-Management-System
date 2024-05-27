<?php
use App\Http\Controllers\Api\V1\Teacher\{
    CoursesController,
    CoursesVideoController, // this is the old controller
    CourseVideosController
};

Route::prefix('teachers/')
    ->middleware(['auth:sanctum', 'teacher'])
    ->group(function () {
        Route::apiResource('courses', CoursesController::class);
        Route::apiResource('courses.videos', CourseVideosController::class);
    });
