<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * return profile-related data.
     */
    public function __invoke()
    {
        $student = Auth::user();
        $courses = $student->coursesEnrollments
        ->map(function ($course) {
            $course['is_favorite'] = $course['pivot']['is_favorite'];
            unset ($course->pivot);
            return $course;
        });
        return response()->json([
            'student' => new StudentResource($student),
            'courses' => $courses
        ]);
    }
}
