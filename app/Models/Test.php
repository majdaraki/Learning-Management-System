<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'test_name',
        'course_id',
    ];

    public function course() : BelongsTo {
        return $this->belongsTo(Course::class);
    }

    public function questions() : HasMany {
        return $this->hasMany(Question::class);
    }
}
