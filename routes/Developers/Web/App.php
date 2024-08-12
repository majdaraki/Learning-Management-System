<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\{
    CategoriesController,
    StudentsController,
    TeachersController,
    WalletsController,
    IssuesController
};
use App\Http\Controllers\Api\V1\{
    CoursesController,
    CourseVideosController,
    CourseQuizzesController,
    QuizQuestionsController,

};


Route::prefix('web/')

    ->middleware(['auth:sanctum'])
    ->group(function () {

        Route::apiResource('courses', CoursesController::class);
        Route::apiResource('courses.videos', CourseVideosController::class);
        Route::apiResource('courses.quizzess', CourseQuizzesController::class);
        Route::apiResource('quizzes.questions', QuizQuestionsController::class);

    });
