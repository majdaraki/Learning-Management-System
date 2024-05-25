<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CourseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category_id' => $this->category_id,
            'category_name' => $this->category_name,
            'description' => $this->description,
            'total_likes' => $this->total_likes,
            'created_from' => $this->created_from,
            'image' => $this->image,
            'videos' => $this->videos,
            'teacher' => $this->teacher,
            'quizzes' => $this->customizeQuizzes($this->quizzes),
        ];
    }

    private function customizeQuizzes($quizzes)
    {
        foreach ($quizzes as &$quiz) {
            if (!is_null($student_grade = Auth::user()->getGrade($quiz))) {
                $quiz['student_grade'] = $student_grade;
            }
            foreach ($quiz['questions'] as &$question) {
                if ($this->getChosenChoice($question)) {
                    $question['chosen_choice_id'] = $this->getChosenChoice($question);
                }
            }
        }
        return $quizzes;
    }

    private function getChosenChoice($question)
    {
        return $question->answers()->pluck('chosen_choice_id')->first();
    }
}
