<?php

namespace App\Http\Controllers\Api\V1\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Teacher\StoreVideoRequest;
use App\Http\Requests\Api\V1\Teacher\UpdateVideoRequest;
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
    public function store(StoreVideoRequest $request, Course $course)
    {
        return DB::transaction(function () use ($request, $course) {
            $request_video = $request->video;
            $video = $this->setMediaName([$request_video], 'Courses/Videos')[0];
            $course->videos()->create(
                array_merge(
                    ['name' => $video],
                    $request->all()
                )
            );
            $this->saveMedia([$request_video], [$video], 'public');
            return $this->sudResponse('Video Created Successfully', 201);
        });

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
    public function update(UpdateVideoRequest $request, Course $course, Video $video)
    {
        return DB::transaction(function () use ($request, $video) {
            $new_video = null;
            if ($request->hasFile('video')) {
                $request_video = $request->video;
                $current_video = $video->name;
                $new_video = $this->setMediaName([$request_video], 'Courses/Videos')[0];
                $this->saveMedia([$request_video], [$new_video], 'public');
                $this->deleteMedia('storage', [$current_video]);
            }

            $video->update([
                'name' => $new_video ? $new_video : $video->name,
                'description' => $request->description
            ]);

            return $this->sudResponse('Video updated successfully.');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course, Video $video)
    {
        return DB::transaction(function () use ($video) {
            $current_video = $video->name;
            $video->delete();
            $this->deleteMedia('storage', [$current_video]);
            return $this->sudResponse('Video Deleted Successfully');
        });
    }
}
