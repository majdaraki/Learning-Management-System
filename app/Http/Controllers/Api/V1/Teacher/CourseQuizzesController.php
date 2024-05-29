<?php

namespace App\Http\Controllers\Api\V1\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\Teacher\QuizResource;
use App\Http\Requests\Api\V1\Teacher\{
    UpdateQuizReuest,
    QuizRequest
};
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Auth,
    DB
};

class CourseQuizzesController extends Controller
{
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
    public function store(QuizRequest $request,Course $course)
    {
        $teacher=Auth::user();

        DB::beginTransaction();

    try {


        $quiz = $course->quizzes()->create($request['quiz']);
        foreach ($request['questions'] as $questionData) {
            $question = $quiz->questions()->create([
                'question_text' => $questionData['question_text'],
            ]);
            foreach ($questionData['choices'] as $choiceData) {
                $question->choices()->create([
                    'choice_text' => $choiceData['text'],
                    'is_correct' => $choiceData['is_correct'],
                ]);
            }
        }

        DB::commit();
        return $this->sudResponse(' Quiz created successfully!');

    } catch (\Exception $e) {

        DB::rollback();

        return response()->json(['message' => 'Failed to create quiz', 'error' => $e->getMessage()], 500);
    }
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * Display the specified resource.
     */
    public function show( $course_id,  $quiz_id)
    {
        $quiz=Quiz::findOrFail($quiz_id);

        return new QuizResource($quiz->load('questions.choices'));
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




        // ...

        public function update(UpdateQuizReuest $request, Course $course,  $quiz_id)

        {
            $quiz=Quiz::findOrFail($quiz_id);
            if ($quiz->course_id != $course->id) {
                return $this->sudResponse('The quiz does not belong to the specified course.');
            }
            $quiz->update($request->all());
            return $this->sudResponse('Quiz updated successfully.');
        }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($course_id,$quiz_id)
    {
        $teacher = Auth::user();
         $quiz=Quiz::findOrFail($quiz_id);
        DB::beginTransaction();

        try {

            $quiz->delete();
            DB::commit();
            return response()->json(['message' => 'Quiz deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to delete quiz'], 500);
        }
    }

}
