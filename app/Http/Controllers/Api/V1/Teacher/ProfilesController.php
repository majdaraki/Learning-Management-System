<?php

namespace App\Http\Controllers\Api\V1\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Student\UpdateProfileRequest;
use App\Http\Resources\TeacherResource;
use App\Models\User;
use App\Traits\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfilesController extends Controller
{
    use Media;
    /**
     * Display a listing of the resource.
     */
    public function index()
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $teacher = Auth::user()->withCount('courses')->where('id',Auth::id())->first();
        // $courses = $teacher->courses->each(function ($course) {
        //     return $course->withCount('students');
        // });
        // [
        //     'courses' => function ($course) {
        //         return $course->withCount('students');
        //     }
        // ]
        return $this->indexOrShowResponse('teacher', $teacher);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $teacher = Auth::user();

            $teacher->update($request->validated());

            if ($request->hasFile('image')) {
                $request_image = $request->image;
                $current_image = $teacher->image()->pluck('name')->first();
                $image = $this->setMediaName([$request_image], 'Teachers')[0];
                $teacher->image()->update(['name' => $image]);
                $this->saveMedia([$request_image], [$image], 'public');
                $this->deleteMedia('storage', [$current_image]);
            }

            return response()->json([
                'message' => 'Profile updated successfully.',
                'teacher' => new TeacherResource($teacher),
            ]);
        });

    }

    /**
     * Remove the specified resource from storage.__________________________________ delete all courses for this teacher .
     */
    public function destroy()
    {
        return DB::transaction(function () {
            $teacher = Auth::user();
            $current_image = $teacher->image()->pluck('name')->first();
            $teacher->image()->delete();
            $teacher->delete();
            $this->deleteMedia('storage', [$current_image]);
            return $this->sudResponse('Profile Deleted Successfully');
        });

    }
}
