<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailsCourse extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->getCategoryNameAttribute(),
            'total_likes' => $this->total_likes,
            'teacher' => $this->getTeacherNameAttribute(),
            'description' => $this->description,
            'image' => $this->getImageAttribute(),
            'videos' =>$this-> getVideosAttribute(),
            'quizzes' => QuizResource::collection($this->whenLoaded('quizzes')),
            'students_count' => $this->students()->count(),
        ];
    }
}
