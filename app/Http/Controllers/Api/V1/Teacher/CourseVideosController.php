<?php

namespace App\Http\Controllers\Api\V1\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Video;
use App\Traits\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseVideosController extends Controller
{
    use Media;
    /**
     * Display a listing of the resource.
     */
    public function index(Course $course)
    {
        //
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
     */
    public function store(Request $request, Course $course)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course, Video $video)
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
    public function update(Request $request, Course $course, Video $video)
    {
        return DB::transaction(function () use ($request, $video) {
            $request_video = $request->video;
            $current_video = $video->name;
            $new_video = $this->setMediaName([$request_video], 'Videos')[0];
            $video->update(['name' => $new_video]);
            $this->saveMedia([$request_video], [$new_video], 'public');
            $this->deleteMedia('storage', [$current_video]);

            return $this->sudResponse('Video updated successfully.');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course, Video $video)
    {
        return DB::transaction(function () use($course,$video) {
            $current_video = $video->name;
            $course->videos()->where('id',$video->id)->delete();
            $this->deleteMedia('storage', [$current_video]);
            return $this->sudResponse('Video Deleted Successfully');
        });
    }
}
