<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\Admin\{
    UpdateStatus
};
use Illuminate\Support\Facades\{
    Auth,
    DB
};
use App\Traits\Media;
class StudentController extends Controller
{
    use Media;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students_not_active = User::role('student')->where('status','inactive')->get();
        $students_active=User::role('student')->where('status','active')->get();
        $students=[
         'students_active'=>$students_active,
           'students_not_active'=>$students_not_active
        ];
        return $this->indexOrShowResponse('students', $students);
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
    public function show(User $student)
    {
        $data = [
            'student' => $student,
            'courses' => $student->coursesEnrollments()->get(),
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
    public function update(UpdateStatus $request, User $student)
    {
       $student->update($request->all());
        return $this->sudResponse('update status of student');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $student)
    {

        return DB::transaction(function () use ($student) {
            $current_image = $student->image()->pluck('name')->first();
            $student->image()->delete();
            $student->delete();
            $this->deleteMedia(
                'storage',
                array_merge([$current_image])
            );
            return $this->sudResponse('Student Deleted Successfully');
        });
    }
}
