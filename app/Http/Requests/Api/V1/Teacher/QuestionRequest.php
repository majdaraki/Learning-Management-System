<?php

namespace App\Http\Requests\Api\V1\Teacher;

use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
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
            'questions' => 'required|array',
            'questions.*.question_text' => 'required|string',
            'questions.*.choices' => 'required|array',
            'questions.*.choices.*.choice_text' => 'required|string',
            'questions.*.choices.*.is_correct' => 'required|boolean',
        ];
    }
}
