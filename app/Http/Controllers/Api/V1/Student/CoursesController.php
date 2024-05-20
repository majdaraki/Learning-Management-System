<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Filters\CourseFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Student\EnrollRequest;
use App\Http\Requests\Api\V1\Student\UpdateEnrollmentRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Policies\CoursePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoursesController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct(
        protected CourseFilters $courseFilters
    ) {
        $this->authorizeResource(Course::class,'course');
    }

    /**
     * search about available courses.
     */
    public function index()
    {
        $courses = $this->courseFilters->applyFilters(Course::query())->get();
        return $this->indexOrShowResponse('courses', $courses);
    }

    /**
     * enroll in a new course.
     */
    public function store(EnrollRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $student = Auth::user();

            $student->coursesEnrollments()->syncWithoutDetaching([
                $request->course_id => [
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);

            return $this->sudResponse('You\'ve enrolled in this course successfully.');
        });
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course)
    {
        return $this->indexOrShowResponse('course', new CourseResource($course->load(['tests.questions.choices', 'teacher'])));
    }

    /**
     * Update the enrollment is_favorite attribute.
     */
    public function update(UpdateEnrollmentRequest $request, Course $course)
    {
        return DB::transaction(function () use ($request, $course) {
            $student = Auth::user();

            $enrollment = $student->enrollments()->where('course_id', $course->id)->firstOrFail();

            $student->coursesEnrollments()->sync([
                $course->id => [
                    'is_favorite' => $request['is_favorite'],
                    'is_active' => $enrollment['is_active'],
                    'updated_at' => now(),
                ],
            ]);
            return $this->sudResponse('Updated successfully.');
        });

    }

    /**
     * Cancel the enrollment.
     */
    public function destroy(Course $course)
    {
        return DB::transaction(function () use ($course) {
            $student = Auth::user();
            $enrollment = $student->enrollments()->where('course_id', $course->id)->firstOrFail();

            $student->coursesEnrollments()->sync([
                $course->id => [
                    'is_favorite' => $enrollment['is_favorite'],
                    'is_active' => false,
                    'updated_at' => now(),
                ],
            ]);

            return $this->sudResponse('Enrollment deleted successfuly.');
        });

    }

    /**
     * Display the favorite courses list.
     */
    public function getFavoritesList()
    {
        $favorites = Auth::user()->favoriteCourses->map(function ($course) {
            unset ($course->pivot);
            return $course;
        });
        return $this->indexOrShowResponse('favorites', $favorites);
    }

    /**
     * Display enrolled courses list.
     */
    public function getEnrollmentsList()
    {
        $courses = Auth::user()->coursesEnrollments->map(function ($course) {
            unset ($course->pivot);
            return $course;
        });
        return $this->indexOrShowResponse('courses', $courses);
    }

}
