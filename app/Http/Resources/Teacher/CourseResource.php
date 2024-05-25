<?php
namespace App\Http\Resources\Teacher;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  IlluminateHttpRequest  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'category_name'=>$this->getCategoryNameAttribute(),
            'name' => $this->name,
            'description' => $this->description,
            'student_count' => $this->students_count,
        ];
    }
}
