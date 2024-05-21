<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Choice;

class ChoicesBelongToQuestion implements ValidationRule
{
    private $questionIds;

    public function __construct($questionIds)
    {
        $this->questionIds = $questionIds;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
       // dd($this->questionIds);
        foreach ($this->questionIds as $key => $value) {

        }
        // Check if all choice IDs belong to the specified question ID
        $validChoiceIds = Choice::where('question_id', $this->questionIds)
            ->pluck('id')
            ->toArray();

            if (!in_array($value, $validChoiceIds)) {
                $fail("The choice with ID $value does not belong to the specified question.");
        }
    }
}
