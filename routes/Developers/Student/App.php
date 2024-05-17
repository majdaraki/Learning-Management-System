<?php
use App\Http\Controllers\Api\V1\Student\{
    HomePageController,
    CategoriesController,
    CoursesController
};

Route::prefix('students')->group(function () {
    Route::get('home',HomePageController::class);

    Route::apiResource('categories',CategoriesController::class)->only('show');
    Route::apiResource('courses',CoursesController::class);
});
