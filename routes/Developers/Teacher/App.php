<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\{
    CoursesController,
    CourseVideosController,
    CourseQuizzesController,
    QuizQuestionsController,

};
use App\Http\Controllers\Api\V1\Teacher\{
    ProfilesController,
    CategoriesController

};




Route::apiResource('categories', CategoriesController::class);


Route::prefix('teachers/')
->middleware(['auth:sanctum','teacher','verified','active'])

    ->group(function () {
        Route::get('profile', [ProfilesController::class, 'show']);
        Route::put('profile', [ProfilesController::class, 'update']);
        Route::delete('profile', [ProfilesController::class, 'destroy']);

        /*Route::apiResource('courses', CoursesController::class);
        Route::apiResource('courses.videos', CourseVideosController::class);
        Route::apiResource('courses.quizzess', CourseQuizzesController::class);
        Route::apiResource('quizzes.questions', QuizQuestionsController::class);*/
    });
