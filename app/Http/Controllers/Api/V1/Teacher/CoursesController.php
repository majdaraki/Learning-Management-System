<?php

namespace App\Http\Controllers\Api\V1\Teacher;
use App\Http\Resources\Teacher\{
    CourseResource,
    DetailsCourse

};
use App\Http\Requests\Api\V1\Teacher\{
    UpdateCourseRequest,
    CourseRequest
};
use App\Http\Controllers\Controller;
use App\Models\{
    Course,
    User,
    Category

};
use App\Traits\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Auth,
    DB
};

class CoursesController extends Controller
{
    use Media;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teacher = Auth::user();
        $coursesResource = CourseResource::collection($teacher->courses()->withCount('students')->get());
        return $this->indexOrShowResponse('body', $coursesResource);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * اسم الكورس
     * وصف الكورس
     * فيديو واحد
     *
     */

    public function store(CourseRequest $request)
    {
        return DB::transaction(function () use ($request){

            $teacher=Auth::user();
            $data=$request->all();
            $data['teacher_id']=$teacher->id;
            $course=Course::create($data);

            if($request->has('image')){
                $request_file = $request->file('image');
                $file_name = $this->setMediaName([$request_file])[0];
                $this->saveMedia([$request_file], [$file_name], 'public/Course');
                $course->image()->create(['name'=>$file_name]);
            }
            return $this->sudResponse('Done');

        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        $course->load('quizzes.questions.choices', 'students');
        return new DetailsCourse($course);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * coursesvideocontroller
     * Update the specified resource in storage.
     */

     public function update(UpdateCourseRequest $request, $course_id){

         return DB::transaction(function () use ($request, $course_id) {
            $teacher = Auth::user();
            $course = Course::findOrFail($course_id);
            if ($course->teacher_id != $teacher->id) {
                 return $this->sudResponse('unauthorized');
            }

            $course->update($request->only(['name', 'description']));
            if ($request->hasFile('image')) {
                 $existingImage = $course->image()->first();

            if ($existingImage) {
                $this->deleteMedia('Course', [$existingImage->name]);
                $existingImage->delete();
            }
            $request_file = $request->file('image');
            $file_name = $this->setMediaName([$request_file])[0];
            $course->image()->create(['name' => $file_name]);
              $this->saveMedia([$request_file], [$file_name], 'public/Course');
            }

         return $this->sudResponse('Course updated successfully.');
    });
  }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        if (Auth::id() === $course->teacher_id) {
        return DB::transaction(function () use ($course) {

            $course->videos()->delete();
            $course->image()->delete();
            $course->delete();
            return $this->sudResponse('course deleted successfully');

    });
}
        return response()->json(['message' => 'Unauthorized to delete this course.'], 200);

    }

}
