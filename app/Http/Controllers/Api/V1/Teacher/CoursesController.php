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
    Category

};
use App\Traits\Media;
use App\Traits\Responses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Auth,
    DB
};

class CoursesController extends Controller
{
    use Media,Responses;
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
     */

    public function store(CourseRequest $request)
    {

        return DB::transaction(function () use ($request){

            $teacher=Auth::user();
            //return $teacher;
            $data=$request->all();
            $data['teacher_id']=$teacher->id;
            $course=Course::create($data);

            if($request->has('video')){
                $request_file = $request->file('video');
                $file_name = $this->setMediaName([$request_file])[0];

                $course->videos()->create(['name' => $file_name]);
                $this->saveMedia([$request_file], [$file_name], 'public/Course');
            }

            if($request->has('image')){
                $request_file = $request->file('image');
                $file_name = $this->setMediaName([$request_file])[0];

                $course->videos()->create(['name' => $file_name]);
                $this->saveMedia([$request_file], [$file_name], 'public/Course');
            }
            return $this->sudResponse('Done');

        });

    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(UpdateCourseRequest $request,  $course_id)
    {
        $teacher=Auth::user();
        $course=Course::find($course_id);
        if ($course->teacher_id != Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return DB::transaction(function () use ($request, $course) {

         $course->update($request->all());

            if ($request->hasFile('image')) {

                $request_image = $request->file('image');
                $image_name = $this->setMediaName([$request_image])[0];
                Storage::delete('public/Course' . $existingVideo->name);
                $course->image()->create(['name' => $image_name]);
                $this->saveMedia([$request_image], [$image_name], 'public/Course');
            }


            if ($request->hasFile('video')) {
                $request_video = $request->file('video');
                $video_name = $this->setMediaName([$request_video])[0];
                $existingVideo = $course->videos()->first();
                if ($existingVideo) {

                    $this->deleteMedia('public/Course/' , [$existingVideo->name]);

                    $existingVideo->name = $video_name;
                    $existingVideo->save();
                } else {

                    $course->videos()->create(['name' => $video_name]);
                }


                $request_video->storeAs('public/Course', $video_name);
            }

            return response()->json(['message' => 'Course updated successfully']);
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
