<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'course_id',
        'is_favorite',
        'is_active',
    ];


    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function Test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }


}
