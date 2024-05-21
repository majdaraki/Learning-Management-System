<?php

namespace App\Http\Requests\Api\V1\Student;

use App\Rules\ChoicesBelongToQuestion;
use App\Rules\QuestionsBelongToTest;
use Illuminate\Foundation\Http\FormRequest;

class StoreTestAnswersRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

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
            'test_id' => ['required', 'exists:tests,id'],
            'answers' => ['required', 'array'],
            'answers.*.question_id' => ['required', 'exists:questions,id', 'distinct', new QuestionsBelongToTest($this->input('test_id'))],
            'answers.*.chosen_choice_id' => ['required', 'distinct', new ChoicesBelongToQuestion($this->input('answers.*.question_id'))]
        ];
    }
}
