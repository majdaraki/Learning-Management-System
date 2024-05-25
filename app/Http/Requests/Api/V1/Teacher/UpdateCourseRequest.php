<?php

namespace App\Http\Requests\Api\V1\Teacher;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
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
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'video'=>['nullable','file'],
            'image'=>['nullable','image'],
        ];
    }
}
