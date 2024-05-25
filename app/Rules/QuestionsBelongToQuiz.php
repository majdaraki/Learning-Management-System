<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Question;

class QuestionsBelongToQuiz implements ValidationRule
{
    private $quiz_id;

    public function __construct($quiz_id)
    {
        $this->quiz_id = $quiz_id;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validQuestionIds = Question::where('quiz_id', $this->quiz_id)
            ->pluck('id')
            ->toArray();

            if (!in_array($value, $validQuestionIds)) {
                $fail("The question with ID $value does not belong to the specified quiz.");
            }
    }
}
