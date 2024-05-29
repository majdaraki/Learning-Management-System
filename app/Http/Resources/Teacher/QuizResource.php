<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->quiz_name,
            'after_video'=>$this->after_video,
            'timer'=>$this->timer,
            'questions' => QuestionResource::collection($this->whenLoaded('questions')),
        ];
    }
}
