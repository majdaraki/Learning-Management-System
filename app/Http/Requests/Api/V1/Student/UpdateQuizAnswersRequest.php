<?php

namespace App\Http\Requests\Api\V1\Student;

use App\Models\Quiz;
use App\Rules\QuestionsBelongToQuiz;
use Illuminate\Foundation\Http\FormRequest;

class UpdateQuizAnswersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $quizzes_count = $this->quiz->course->quizzes()->count();
        return [
            'quiz_number' => ['required', 'integer', 'min:1', "max:$quizzes_count"],
            'answers' => ['required', 'array'],
            'answers.*.question_id' => ['required', 'distinct', new QuestionsBelongToQuiz($this->quiz->id)],
            'answers.*.chosen_choice_id' => ['required', 'distinct']
        ];
    }
}
