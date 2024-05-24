<?php

namespace App\Http\Requests\Api\V1\Student;

use App\Models\Test;
use App\Rules\ChoicesBelongToQuestion;
use App\Rules\QuestionsBelongToTest;
use Illuminate\Foundation\Http\FormRequest;

class StoreTestAnswersRequest extends FormRequest
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
        $tests_count = Test::findOrFail($this->input('test_id'))->course->tests()->count();
        return [
            'test_id' => ['required', 'exists:tests,id'],
            'test_number' => ['required', 'integer', 'min:1', "max:$tests_count"],
            'answers' => ['required', 'array'],
            'answers.*.question_id' => ['required', 'distinct', new QuestionsBelongToTest($this->input('test_id'))],
            'answers.*.chosen_choice_id' => ['required', 'distinct']
        ];
    }
}
