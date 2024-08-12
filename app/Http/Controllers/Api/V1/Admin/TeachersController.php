<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\Admin\{
    UpdateStatus
};
use App\Models\Course;
use Illuminate\Support\Facades\{
    Auth,
    DB
};
use Illuminate\Support\Facades\Notification;
use App\Notifications\updateStatusForTeacherOrStident;
use App\Traits\Media;
class TeachersController extends Controller
{
    use Media;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers_not_active = User::role('teacher')->where('status','inactive')->get();
        $teacher_active=User::role('teacher')->where('status','active')->get();
        $teachers=[
         'teachers_active'=>$teacher_active,
           'teachers_inactive'=>$teachers_not_active
        ];
        return $this->indexOrShowResponse('teacher', $teachers);
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
    public function store(Request $teacher)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $teacher)
    {
        $data = [
            'teacher' => $teacher,
            'courses' => $teacher->courses()->get(),
        ];
        return $this->indexOrShowResponse('body', $data);
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
    public function update(UpdateStatus $request, User $teacher)
    {
        $teacher->update($request->all());
        Notification::route('mail', $teacher->email)
        ->notify(new updateStatusForTeacherOrStident($teacher, $request->status));
        return $this->sudResponse('update status of Teacher');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $teacher)
    {
        return DB::transaction(function () use ($teacher) {
            $current_image_teacher = $teacher->image()->pluck('name')->first();
            $current_image_courses = [];
            $current_videos = [];

            foreach ($teacher->courses as $course) {
                $current_videos =  $course->videos()->pluck('name')->toArray();
                $current_image_courses[] = $course->image()->pluck('name')->first();
            }
            foreach ($teacher->courses as $course) {
                $course->image()->delete();
                $course->videos()->delete();
            }
            $teacher->image()->delete();
            $teacher->courses()->delete();
            $teacher->delete();
            $all_media = array_merge($current_image_courses, $current_videos,[$current_image_teacher]);
            $this->deleteMedia('storage', $all_media);
           // $this->deleteMedia('storage',$current_videos);
            return $this->sudResponse('Teacher deleted successfully');
        });
    }

}
