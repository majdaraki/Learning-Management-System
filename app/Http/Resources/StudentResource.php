<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'created_from' => $this->created_at->diffForHumans(),
            'image' => $this->image,
            'wallet' => [
                'balance' => $this->wallet->balance,
                'points' => $this->wallet->points,
            ]
        ];
    }
}
