<?php

namespace App\Http\Requests\Api\V1\Teacher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $videos_ids = $this->course->videos()->pluck('id')->toArray();
        $video_id = ($this->segments()[6]);
        return ($this->course->teacher_id == Auth::id()) && (in_array($video_id,$videos_ids));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'video' => [],
            'description' => ['string'],
        ];
    }
}
