<?php

namespace App\Http\Controllers\Api\V1\Teacher;

use App\Http\Resources\Teacher\{
    CourseResource,
    DetailsCourse

};
use App\Http\Requests\Api\V1\Teacher\{
    UpdateCourseRequest,
    StoreCourseRequest
};
use App\Http\Controllers\Controller;
use App\Models\{
    Course
};
use App\Traits\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Auth,
    DB
};
use App\Filters\CourseFilters;
use Illuminate\Auth\Access\AuthorizationException;

class CoursesController extends Controller
{
    use Media;

    /**
     * Create the controller instance.
     */
    public function __construct(
        protected CourseFilters $courseFilters
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(CourseFilters $courseFilters)
    {
        $teacher = Auth::user();
        $courses = $courseFilters->applyFilters(
            $teacher->courses()->withCount('students')->getQuery()
        )->get();

        return $this->indexOrShowResponse('courses', $courses);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(StoreCourseRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $teacher = Auth::user();

            $course = $teacher->courses()->create($request->all());

            if ($request->hasFile('image')) {
                $request_image = $request->image;
                $image = $this->setMediaName([$request_image], 'Courses')[0];
                $course->image()->create(['name' => $image]);
                $this->saveMedia([$request_image], [$image], 'public');
            }
            return $this->sudResponse('Course created successfully', 201);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        $course->load('quizzes.questions.choices');
        return $this->indexOrShowResponse('course',new DetailsCourse($course));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(UpdateCourseRequest $request, Course $course)
    {
        return DB::transaction(function () use ($request, $course) {
            $teacher = Auth::user();
            $course->update($request->validated());

            if ($request->hasFile('image')) {
                $request_image = $request->image;
                $current_image = $course->image()->pluck('name')->first();
                $image = $this->setMediaName([$request_image], 'Courses')[0];
                $course->image()->update(['name' => $image]);
                $this->saveMedia([$request_image], [$image], 'public');
                $this->deleteMedia('storage', [$current_image]);
            }

            return $this->sudResponse('Course updated successfully.');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $teacher = Auth::user();
        throw_if($course->teacher_id != $teacher->id, new AuthorizationException());

        return DB::transaction(function () use ($teacher, $course) {
            $current_image = $course->image()->pluck('name')->first();
            $current_videos = $course->videos()->pluck('name')->toArray();
            $course->image()->delete();
            $course->videos()->delete();
            $course->delete();
            $this->deleteMedia(
                'storage',
                array_merge([$current_image], $current_videos)
            );
            return $this->sudResponse('Course Deleted Successfully');
        });
    }

}
