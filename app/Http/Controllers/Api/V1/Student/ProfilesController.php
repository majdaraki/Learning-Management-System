<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Student\UpdateProfileRequest;
use App\Http\Resources\StudentResource;
use App\Models\User;
use App\Traits\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Auth,
    DB
};

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
        $student = Auth::user();
        $courses = $student->coursesEnrollments
            ->map(function ($course) {
                $course['is_favorite'] = $course['pivot']['is_favorite'];
                unset ($course->pivot);
                return $course;
            });
        return response()->json([
            'student' => new StudentResource($student->load('wallet')),
            'enrolled_courses' => $courses
        ]);
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
            $student = Auth::user();

            $student->update($request->validated());

            if ($request->hasFile('image')) {
                $request_image = $request->image;
                $current_image = $student->image()->pluck('name')->first();
                $image = $this->setMediaName([$request_image],'Students')[0];
                $student->image()->update(['name' => $image]);
                $this->saveMedia([$request_image], [$image], 'public');
                $this->deleteMedia('storage', [$current_image]);
            }

            return response()->json([
                'message' => 'Profile updated successfully.',
                'student' => new StudentResource($student),
            ]);
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        return DB::transaction(function () {
            $student = Auth::user();
            $current_image = $student->image()->pluck('name')->first();
            $student->image()->delete();
            $student->delete();
            $this->deleteMedia('storage', [$current_image]);
            return $this->sudResponse('Profile Deleted Successfully');
        });
    }
}
