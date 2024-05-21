<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Course extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'category_id',
        'total_likes',
    ];

    protected $appends = [
        'created_from',
        'videos',
        'teacher_name',
        'category_name',
    ];


    public function getVideosAttribute()
    {
        return $this->videos()
            ->get(['mediable_type', 'name'])
            ->map(function ($media) {
                $dir = explode('\\', $media->mediable_type)[2];
                unset ($media->mediable_type);
                return asset("storage/$dir") . '/' . $media->name;
            });
    }

    public function getTeacherNameAttribute()
    {
        return $this->teacher()->pluck('first_name')->first();
    }

    public function getCategoryNameAttribute()
    {
        return $this->category()->pluck('name')->first();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tests(): HasMany
    {
        return $this->hasMany(Test::class);
    }

    public function videos(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function Students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollments')
            ->withPivot([
                'is_favorite',
                'student_has_enrolled',
                'progress',
            ]);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
