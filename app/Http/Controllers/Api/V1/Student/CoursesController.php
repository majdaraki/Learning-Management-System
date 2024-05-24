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
        $this->authorizeResource(Course::class, 'course');
    }

    /**
     * search about available courses in search box.
     */
    public function index()
    {
        $courses = $this->courseFilters->applyFilters(Course::query())->with('teacher')->get();
        return $this->indexOrShowResponse('courses', $courses);
    }

    /**
     * enroll in a new course.
     */
    public function store(EnrollRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $student = Auth::user();

            $enrollment = $student->enrollments()
                ->where('course_id', $request->course_id)
                ->firstOrCreate(values: [
                    'course_id' => $request->course_id,
                    'student_has_enrolled' => true
                ]);

            if (!is_null($enrollment)) {
                $enrollment->update(['student_has_enrolled' => true]);
            }

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

            $enrollment = $student->enrollments()
                ->where('course_id', $course->id)
                ->firstOrCreate(values: array_merge(['course_id' => $course->id], $request->only('is_favorite')));

            if (!is_null($enrollment)) {
                $enrollment->update($request->only('is_favorite'));
            }

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

            $student->coursesEnrollments()->syncWithoutDetaching([
                $course->id => [
                    'is_favorite' => $enrollment['is_favorite'],
                    'student_has_enrolled' => false,
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
        // $favorites = Auth::user()->favoriteCourses->map(function ($course) {
        //     unset ($course->pivot);
        //     return $course;
        // });
        return $this->indexOrShowResponse('favorites', Auth::user()->favoriteCourses()->with('teacher')->get());
    }

    /**
     * Display enrolled courses list.
     */
    public function getEnrollmentsList()
    {
        // $courses = Auth::user()->coursesEnrollments->map(function ($course) {
        //     unset ($course->pivot);
        //     return $course;
        // });
        return $this->indexOrShowResponse('courses', Auth::user()->coursesEnrollments()->with('teacher')->get());
    }

}
