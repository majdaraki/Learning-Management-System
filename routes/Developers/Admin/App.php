<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\{
    CategoriesController,
    StudentController,
    TeacherController,
    CourseController,
    WalletController



};
use App\Http\Controllers\Api\V1\{
    CoursesController,
    CourseVideosController,
    CourseQuizzesController,
    QuizQuestionsController,

};


Route::prefix('admin/')

    ->middleware(['auth:sanctum','admin'])
    ->group(function () {

        Route::apiResource('categories',CategoriesController::class);

        Route::apiResource('course', CoursesController::class);
        Route::apiResource('courses.videos', CourseVideosController::class);
        Route::apiResource('courses.quizzess', CourseQuizzesController::class);
        Route::apiResource('quizzes.questions', QuizQuestionsController::class);

        Route::apiResource('students',StudentController::class);
        Route::apiResource('teachers',TeacherController::class);
        Route::apiResource('courses',CourseController::class);
        Route::put('wallet/{id}',[WalletController::class,'update']);
    });
