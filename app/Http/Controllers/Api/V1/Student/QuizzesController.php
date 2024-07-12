<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Student\StoreQuizAnswersRequest;
use App\Models\{
    Choice,
    Quiz
};
use App\Models\Answer;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Auth,
    DB,
    Validator
};
use Illuminate\Validation\ValidationException;

class QuizzesController extends Controller
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
     * Store a newly created resource in storage.
     */
    public function store(StoreQuizAnswersRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $student = Auth::user();
            $wallet = $student->wallet;
            $quiz = Quiz::findOrFail($request->quiz_id);
            $course = $quiz->course;
            $quizzes_count = $course->quizzes()->count();
            $questions_count = $quiz->questions()->count();
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
                    throw $e;
                }

                ($choice->is_correct) ? $correct_answers_count++ : '';
                $student->answers()->create($answer);
            }
            $grade = $correct_answers_count / $questions_count * 100;

            $student->results()
                ->create([
                    'quiz_id' => $request->quiz_id,
                    'grade' => $grade,
                ]);

            $progress = $request->quiz_number / $quizzes_count * 100;
            $student->enrollments()
                ->where('course_id', $course->id)
                ->update(['progress' => $progress]);

            if ($progress == 100) {
                $wallet->points += 100;
                $wallet->save();

                return response()->json([
                    'message' => 'Congrats! You\'ve earned extra 100 points for completing this course.',
                    'grade' => $grade,
                ]);
            }

            return response()->json([
                'message' => 'Congratiolations!',
                'grade' => $grade,
            ]);

        });

    }

    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz)
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
    public function update(Request $request, Quiz $quiz)
    {dd('not working yet.');
        return DB::transaction(function () use ($request, $quiz) {
            $student = Auth::user();
            $wallet = $student->wallet;
            // $quiz = Quiz::findOrFail($request->quiz_id);
            $course = $quiz->course;
            $quizzes_count = $course->quizzes()->count();
            $questions_count = $quiz->questions()->count();
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
                    throw $e;
                }

                ($choice->is_correct) ? $correct_answers_count++ : '';

                $data = [
                    'user_id' => $student->id,
                    'question_id' => $answer['question_id'],
                    'chosen_choice_id' => $answer['chosen_choice_id'],
                ];

                Answer::updateOrInsert(
                    ['user_id' => $student->id, 'question_id' => $answer['question_id']],
                    $data
                );
            }
            $grade = $correct_answers_count / $questions_count * 100;

            $data = [
                'user_id' => $student->id,
                'quiz_id' => $quiz->id,
                'grade' => $grade,
            ];

            Result::updateOrInsert(
                ['user_id' => $student->id, 'quiz_id' => $quiz->id],
                $data
            );

            // $student->results()->where('quiz_id', $request->quiz_id)
            //     ->update(['grade' => $grade]);

            $progress = $request->quiz_number / $quizzes_count * 100;
            $enrollment = $student->enrollments()
                ->where('course_id', $course->id)
                ->firstOrCreate(values: array_merge(['course_id' => $course->id], $request->only('is_favorite')));

            if (!is_null($enrollment)) {
                $enrollment->update(['progress' => $progress]);
            }

            // $student->enrollments()
            //     ->where('course_id', $course->id)
            //     ->update(['progress' => $progress]);

            if ($progress == 100) {
                $wallet->points += 100;
                $wallet->save();

                return response()->json([
                    'message' => 'Congrats! You\'ve earned extra 100 points for completing this course.',
                    'grade' => $grade,
                ]);
            }

            return response()->json([
                'message' => 'Congratiolations!',
                'grade' => $grade,
            ]);

        });

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {
        //
    }
}
