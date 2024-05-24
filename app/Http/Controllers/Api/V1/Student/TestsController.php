<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Student\StoreTestAnswersRequest;
use App\Models\Choice;
use App\Models\Course;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class TestsController extends Controller
{
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
     * Store student answers for a test.
     */
    public function store(StoreTestAnswersRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $student = Auth::user();
            $test = Test::findOrFail($request->test_id);
            $course = $test->course;
            $tests_count = $course->tests()->count();
            $questions_count = count($request->answers);
            $correct_answers_count = 0;

            foreach ($request->answers as $answer) {

                try {
                    $choice = Choice::where('question_id', $answer['question_id'])->find($answer['chosen_choice_id']);

                    if (is_null($choice)) {
                        $validator = Validator::make([], []); // Empty data and rules fields
                        $validator->errors()->add('chosen_choice_id', 'choice with id : ' . $answer['chosen_choice_id'] . ' doesn\'t correspond to the question with id : ' . $answer['question_id'] . ' .');

                        throw new ValidationException($validator);
                    }
                } catch (ValidationException $e) {
                    // Handle the exception or re-throw it if needed
                    throw $e;
                }

                ($choice->is_correct) ? $correct_answers_count++ : '';
                $student->answers()->create($answer);
            }
            $grade = $correct_answers_count / $questions_count * 100;

            $student->results()
                ->create([
                    'test_id' => $request->test_id,
                    'grade' => $grade,
                ]);

            $progress = $request->test_number / $tests_count * 100;
            $student->enrollments()
                ->where('course_id', $course->id)
                ->update(['progress' => $progress]);

            return $this->sudResponse('Congrats! You\'ve got : ' . $grade . '%  in this test.');

        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Test $test)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Test $test)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Test $test)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Test $test)
    {
        //
    }
}
