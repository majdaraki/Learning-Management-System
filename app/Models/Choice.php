<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Choice extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'choice_text',
        'question_id',
        'is_correct',
    ];


    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function Students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'answers', 'chosen_choice_id');
    }

}
