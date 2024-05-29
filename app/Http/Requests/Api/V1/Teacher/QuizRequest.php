<?php

namespace App\Http\Requests\Api\V1\Teacher;

use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Foundation\Http\FormRequest;

class QuizRequest extends FormRequest
{
    // protected $stopOnFirstFailure = true;

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

        return [
            'quiz' => 'required|array',
            'quiz.quiz_name' => 'required|string|max:255',
            'quiz.after_video' => 'required|numeric',
            'quiz.timer' => 'required|integer|min:1',
            'questions' => 'required|array',
            'questions.*.question_text' => 'required|string',
            'questions.*.choices' => 'required|array',
            'questions.*.choices.*.text' => 'required|string',
            'questions.*.choices.*.is_correct' => 'required|boolean',
        ];
    }
}
