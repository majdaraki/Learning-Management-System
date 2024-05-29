<?php

namespace App\Http\Requests\Api\V1\Teacher;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequest extends FormRequest
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
            'question_text' => 'nullable|string',
            'choices' => 'nullable|array',
            'choices.*.id' => 'sometimes|required|numeric|exists:choices,id',
            'choices.*.choice_text' => 'required_with:choices|string',
            'choices.*.is_correct' => 'nullable|boolean',
        ];
    }
}
