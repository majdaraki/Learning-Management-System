<?php

namespace App\Http\Requests\Api\V1\Student;

use App\Models\Course;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class EnrollRequest extends FormRequest
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
            'course_id' => ['required', 'exists:courses,id'],
        ];
    }

    // Check if the student hasn't already enrolled in the course.
    public function withValidator(Validator $validator): void
    {
        $user_courses = $this->user()->coursesEnrollments;

        $validator->after(function (Validator $validator) use ($user_courses) {
            $data = $validator->validated();
            if (!empty ($data)) {
                $course = Course::find($data['course_id']);
                if ($user_courses->contains($course)) {
                    $validator->errors()->add('course_id', 'You\'ve already enrolled in this course.');
                }
            }
        });
    }
}
