<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CoursesPolicy
{

    public function before(User $user, string $ability): bool|null
{
    if ($user->hasRole('admin')) {
        return true;
    }
    return null;
}

    public function viewAny(User $user): bool
    {
        return true;
    }


    public function view(User $user, Course $course): bool
    {
        return true;
    }


    public function create(User $user): bool
    {
        return true;
    }


    public function update(User $user, Course $course): bool
    {

        return $user->id===$course->teacher_id;

    }

    public function delete(User $user, Course $course): bool
    {
        return $user->id===$course->teacher_id;
    }



}
