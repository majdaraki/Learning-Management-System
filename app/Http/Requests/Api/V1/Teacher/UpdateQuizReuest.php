<?php

namespace App\Http\Requests\Api\V1\Teacher;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;

class UpdateQuizReuest extends FormRequest
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
            'quiz_name' => 'nullable|string',
            'after_video' => 'nullable|boolean',
            'timer' => 'nullable|integer|min:1',
        ];
    }
}
