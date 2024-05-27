<?php
use App\Http\Controllers\Api\V1\Teacher\{
    CoursesController,
    CoursesVideoController, // this is the old controller
    CourseVideosController,
    CourseQuizzesController,
    QuizQuestionsController
};

Route::prefix('teachers/')
    ->middleware(['auth:sanctum', 'teacher'])
    ->group(function () {
        Route::apiResource('courses', CoursesController::class);
        Route::apiResource('courses.videos', CourseVideosController::class)->except('index', 'show');
        Route::apiResource('courses.quizzess', CourseQuizzesController::class);
        Route::apiResource('quizzes.questions', QuizQuestionsController::class)->except('index', 'show');
    });
