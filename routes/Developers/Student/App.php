<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Student\{
    HomePageController,
    CategoriesController,
    CoursesController,
    ProfilesController,
    QuizzesController,
    TeachersController,
    IssuesController
};

Route::prefix('students/')
    ->middleware(['auth:sanctum', 'student','verified','active'])
    ->group(function () {
        Route::get('profile', [ProfilesController::class , 'show']);
        Route::put('profile', [ProfilesController::class , 'update']);
        Route::delete('profile', [ProfilesController::class , 'destroy']);

        Route::get('home', HomePageController::class);

        Route::apiResource('categories', CategoriesController::class)->only('show');

        Route::get('courses/enrollments', [CoursesController::class, 'getEnrollmentsList']);
        Route::get('courses/favorites', [CoursesController::class, 'getFavoritesList']);
        Route::apiResource('courses', CoursesController::class);

        Route::apiResource('quizzes', QuizzesController::class)->only('store');

        Route::apiResource('teachers',TeachersController::class)->only('show');

        Route::apiResource('issues',IssuesController::class)->only('store');
    });
