<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Teacher\{
    DetailsCourse
};
use App\Http\Requests\Api\V1\Admin\{
    UpdateStatus
};
use App\Http\Requests\Api\V1\Teacher\{
    UpdateCourseRequest,
    StoreCourseRequest
};
use App\Http\Controllers\Controller;
use App\Models\{
    Course,
    User
};
use App\Traits\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Auth,
    DB
};
use Illuminate\Support\Facades\Notification;
use App\Notifications\CreateCourses;
use App\Notifications\UpdateStatusForCourse;
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
        $this->authorizeResource(Course::class, 'course');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(CourseFilters $courseFilters)
    {
        $user = Auth::user();

        if ($user->can('CRUD_COURSE')) {
            $courses = Course::withCount('enrolledStudents')->get();
        } else {
            $courses = $courseFilters->applyFilters(
                $user->courses()->withCount('enrolledStudents')->getQuery()
            )->get();
        }
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
            $students=User::role('student')->get();
            foreach($students as $student){
                Notification::route('mail', $student->email)
                ->notify(new CreateCourses($student,$course->name,$teacher));
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
        return $this->indexOrShowResponse('course', new DetailsCourse($course));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(UpdateCourseRequest $request1, UpdateStatus $request2, Course $course)
    {

       $teacher=$course->teacher;
        //$this->authorize('update',$course);
        return DB::transaction(function () use ($request1, $request2,$course,$teacher) {
            $user = Auth::user();
            $course->update($request1->validated());
            if($user->hasRole('admin')){
                Notification::route('mail', $teacher->email)
                ->notify(new UpdateStatusForCourse($teacher,$request2->status,$course->name));
                $course->update($request2->validated());
            }

            if ($request1->hasFile('image')) {
                $request_image = $request1->image;
                $current_image = $course->image()->pluck('name')->first();
                $image = $this->setMediaName([$request_image], 'Courses')[0];
                if ($current_image) {
                    $course->image()->update(['name' => $image]);
                } else {
                    $course->image()->create(['name' => $image]);
                }
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
