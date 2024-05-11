<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Media extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];



    public function mediable()
    {
        return $this->morphTo();
    }


    // public function course(): MorphTo
    // {
    //     return $this->morphTo(Course::class);
    // }

    // public function teacher(): MorphTo
    // {
    //     return $this->morphTo(User::class);
    // }


}
