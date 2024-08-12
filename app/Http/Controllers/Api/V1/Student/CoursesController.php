<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Filters\CourseFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Student\{
    EnrollRequest,
    UpdateEnrollmentRequest
};
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\{
    Auth,
    DB
};


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
        $courses = $this->courseFilters->applyFilters(Course::query())->with([
            'teacher',
            'enrollments' => function ($query) {
                return $query->where('user_id', Auth::id());
            }
        ])->active()->get();

        $courses = $this->costumizeCourses($courses);
        return $this->indexOrShowResponse('courses', $courses);
    }

    /**
     * enroll in a new course.
     */
    public function store(EnrollRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $student = Auth::user();
            $course = Course::findOrFail($request->course_id);
            if (!$student->canBuy($course)) {
                return $this->sudResponse('You don\'t have enough balance to buy this course.', 400);
            }

            $this->subtractBalance($student, $course);

            $enrollment = $student->enrollments()
                ->where('course_id', $course->id)
                ->firstOrCreate(values: [
                    'course_id' => $course->id,
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
        throw_if($course->status != 'active', (new ModelNotFoundException)->setModel(Course::class));
        return $this->indexOrShowResponse(
            'course',
            new CourseResource($course->load([
                'quizzes.questions.choices',
                'teacher',
                'enrollments' => function ($query) {
                    return $query->where('user_id', Auth::id());
                }
            ])->append('videos'))
        );
    }

    /**
     * Update the enrollment is_favorite attribute.
     */
    public function update(UpdateEnrollmentRequest $request, Course $course)
    {
        throw_if($course->status != 'active', (new ModelNotFoundException)->setModel(Course::class));

        return DB::transaction(function () use ($request, $course) {
            $student = Auth::user();

            $enrollment = $student->enrollments()
                ->where('course_id', $course->id)
                ->firstOrCreate(values: array_merge(['course_id' => $course->id], $request->only('is_favorite')));

            if (!is_null($enrollment)) {
                $enrollment->update($request->only('is_favorite'));
            }

            if ($request->is_favorite) {
                $course->total_likes++;
            } else {
                $course->total_likes--;
            }
            $course->save();
            return $this->sudResponse('Updated successfully.');
        });

    }

    /**
     * Cancel the enrollment.
     */
    public function destroy(Course $course)
    {
        throw_if($course->status != 'active', (new ModelNotFoundException)->setModel(Course::class));

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

    /**
     * subtract course cost from the student wallet.
     */
    public function subtractBalance($student, $course): void
    {
        DB::transaction(function () use ($student, $course) {
            $wallet = $student->wallet;

            if ($wallet->points >= $course->price * 100) {
                $wallet->points -= $course->price * 100;
                return;
            }

            $remaining = $course->price - $wallet->points / 100;
            $wallet->points = 0;
            $wallet->balance -= $remaining;
            $wallet->save();
            return;

        });
    }

    public function costumizeCourses($courses)
    {
        foreach ($courses as $course) {
            if (!$course->enrollments->isEmpty()) {
                $course['is_favorite'] = $course['enrollments'][0]['is_favorite'];
                $course['student_has_enrolled'] = $course['enrollments'][0]['student_has_enrolled'];
                $course['progress'] = $course['enrollments'][0]['progress'];
            } else {
                $course['is_favorite'] = false;
                $course['student_has_enrolled'] = false;
                $course['progress'] = 0.0;
            }
            unset($course->enrollments);
        }
        return $courses;
    }
}
