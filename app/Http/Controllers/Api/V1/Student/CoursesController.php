<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Filters\CourseFilters;
use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct(protected CourseFilters $courseFilters)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = $this->courseFilters->applyFilters(Course::query())->get();
        return $this->indexOrShowResponse('courses',$courses);
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
    public function update(Request $request, Course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        //
    }
}
