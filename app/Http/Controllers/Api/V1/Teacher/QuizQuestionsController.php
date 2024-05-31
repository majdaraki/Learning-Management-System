<?php

namespace App\Http\Controllers\Api\V1\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\Teacher\{
    UpdateQuestionRequest,
    QuestionRequest
};
use Illuminate\Support\Facades\{
    Auth,
    DB
};

class QuizQuestionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Quiz $quiz)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QuestionRequest $request, Quiz $quiz)
    {

        foreach ($request['questions'] as $questionData) {
            $question = $quiz->questions()->create([
                'question_text' => $questionData['question_text'],
            ]);
            foreach ($questionData['choices'] as $choiceData) {
                $question->choices()->create([
                    'choice_text' => $choiceData['choice_text'],
                    'is_correct' => $choiceData['is_correct'],
                ]);
            }
        }

        return response()->json(['message' => 'Questions  created successfully.'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz, Question $question)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quiz $quiz)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuestionRequest $request, Quiz $quiz, Question $question)
    {

        if($quiz->id!=$question->quiz_id){
            return $this->sudResponse('The question does not belong to the specified quiz.');
        }
        if (isset($request['question_text'])) {
            $question->question_text = $request['question_text'];
        }
        if (isset($request['choices'])) {
            foreach ($request['choices'] as $choiceData) {
                if (isset($choiceData['id'])) {

                    $question->choices()
                             ->where('id', $choiceData['id'])
                             ->update([
                                 'choice_text' => $choiceData['choice_text'],
                                 'is_correct' => $choiceData['is_correct']
                             ]);
                }
            }
        }
        $question->save();
        return $this->sudResponse('Question updated successfully .');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz,Question $question)
    {
        DB::beginTransaction();
        try {
            $question->delete();
            DB::commit();
            return response()->json(['message' => 'question deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to delete question'], 500);
        }
    }
}
