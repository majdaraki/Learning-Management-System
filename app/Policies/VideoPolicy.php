<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use App\Models\Video;
use Illuminate\Auth\Access\Response;

class VideoPolicy
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


    public function view(User $user, Video $video): bool
    {
        return true;
    }


    public function create(User $user): bool
    {
        return true;
    }


    public function update(User $user, Video $video): bool
    {
        return true;
    }


    public function delete(User $user,Course $course, Video $video): bool
    {
        return true;
    }
}
