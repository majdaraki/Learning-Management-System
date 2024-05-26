<?php

namespace App\Http\Controllers\Api\V1\Teacher;
use App\Http\Resources\Teacher\CourseResource;
use App\Http\Requests\Api\V1\Teacher\{
    UpdateCourseRequest,
    CourseRequest
};
use App\Http\Controllers\Controller;
use App\Models\{
    Course,
    User,
    Category,
    Video

};
use App\Traits\Media;
use App\Traits\Responses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Auth,
    DB
};

class CoursesVideoController extends Controller
{
    use Media;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

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

    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */



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

     public function update(Request $request, $course_id)
        {
      return DB::transaction(function () use ($request, $course_id) {
        $teacher = Auth::user();
        $course = Course::findOrFail($course_id);
        if ($course->teacher_id != $teacher->id) {
          return $this->sudResponse('unauthorized');
       }

        if ($request->filled('delete_video_id')) {
        $video = $course->videos()->find($request->input('delete_video_id'));
        if ($video) {
        $this->deleteMedia('Course', [$video->name]);
        $video->delete();
        }
       }

        if ($request->hasFile('video')) {
            $request_file = $request->file('video');
            $file_name = $this->setMediaName([$request_file])[0];
           $course->videos()->create([
            'name' => $file_name,
            'description' => $request->video_description
         ]);
        $this->saveMedia([$request_file], [$file_name], 'public/Course');
        }


        if ($request->filled('video_id') && $request->filled('video_description')) {
        $video = $course->videos()->find($request->input('video_id'));
        if ($video) {
        $video->update(['description' => $request->input('video_description')]);
        }
     }

     return $this->sudResponse('Videos updated successfully.');
 });
}


        /**
         * Remove the specified resource from storage.
         */
        public function destroy(Course $course)
        {
            //
        }
}
