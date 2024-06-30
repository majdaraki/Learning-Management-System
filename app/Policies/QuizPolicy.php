<?php

namespace App\Policies;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class QuizPolicy
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


    public function view(User $user, Quiz $quiz): bool
    {
        return true;
    }


    public function create(User $user): bool
    {
        return true;
    }


    public function update(User $user, Quiz $quiz): bool
    {
        return true;
    }


    public function delete(User $user, Quiz $quiz): bool
    {
        return true;
    }



}
