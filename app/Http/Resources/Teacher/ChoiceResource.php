<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Resources\Json\JsonResource;

class ChoiceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'choice_text' => $this->choice_text,
            'is_correct' => $this->is_correct,
        ];
    }
}
