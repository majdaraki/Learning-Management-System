<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Question;

class QuestionsBelongToTest implements ValidationRule
{
    private $test_id;

    public function __construct($test_id)
    {
        $this->test_id = $test_id;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validQuestionIds = Question::where('test_id', $this->test_id)
            ->pluck('id')
            ->toArray();

            if (!in_array($value, $validQuestionIds)) {
                $fail("The question with ID $value does not belong to the specified test.");
            }
    }
}
