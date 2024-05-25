<?php
use App\Http\Controllers\Api\V1\Student\{
    HomePageController,
    CategoriesController,
    CoursesController,
    ProfileController,
    QuizzesController,
    TeachersController
};

Route::prefix('students/')
    ->middleware(['auth:sanctum', 'student'])
    ->group(function () {
        Route::get('profile', ProfileController::class);

        Route::get('home', HomePageController::class);

        Route::apiResource('categories', CategoriesController::class)->only('show');

        Route::get('courses/enrollments', [CoursesController::class, 'getEnrollmentsList']);
        Route::get('courses/favorites', [CoursesController::class, 'getFavoritesList']);
        Route::apiResource('courses', CoursesController::class);

        Route::apiResource('tests', QuizzesController::class)->only('store');

        Route::get('teachers/{teacher}', [TeachersController::class, 'show']);
    });
