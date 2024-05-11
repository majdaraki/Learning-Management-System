<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends BaseModel
{
    use HasFactory;

        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'question_text',
        'test_id',
    ];


    public function test() : BelongsTo {
        return $this->belongsTo(Test::class);
    }

    public function choices() : HasMany {
        return $this->hasMany(Choice::class);
    }

    public function Students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'answers');
    }

}
