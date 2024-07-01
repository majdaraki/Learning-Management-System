<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\{
    Category,
    Course
};
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    /**
     * get home page related data.
     */
    public function __invoke(Request $request)
    {
        $recommended_courses = Course::orderBy('total_likes', 'desc')->active()->take(10)->get();
        $latest_courses = Course::latest()->active()->take(10)->get();
        $categories = Category::parents()->get();

        return response()->json([
            'recommended' => $recommended_courses,
            'latest' => $latest_courses,
            'categories' => $categories,
        ]);
    }
}
