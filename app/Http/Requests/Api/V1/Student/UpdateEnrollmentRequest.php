<?php

namespace App\Http\Requests\Api\V1\Student;

use App\Models\Course;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEnrollmentRequest extends FormRequest
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
            'is_favorite' => ['required', 'boolean']
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $user_courses = $this->user()->coursesEnrollments;

        $validator->after(function (Validator $validator) use ($user_courses) {
            if (!$user_courses->contains($this->course)) {
                $validator->errors()->add('course_id', 'You haven\'t enrolled in this course yet.');
            }
        });
    }
}
