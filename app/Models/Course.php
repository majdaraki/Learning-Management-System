<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Auth;

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
        'price',
        'category_id',
        'total_likes',
        'teacher_id',
        'description',
        'status',
    ];
    
    protected $appends = [
        'category_name',
        'created_from',
        'image',
    ];

    public function getImageAttribute()
    {
        $image = $this->image()->pluck('name')->first();
        if (!$image) {
            return '';
        }
        return asset('storage/' . $image);
    }


    public function getVideosAttribute()
    {
        return $this->videos()
            ->get(['videoable_type', 'name', 'description', 'id'])
            ->map(function ($video) {
                $dir = explode('\\', $video->videoable_type)[2];
                unset($video->videoable_type);
                return [
                    'id' => $video->id,
                    'name' => asset('storage/' . $video->name),
                    'description' => $video->description,
                ];
            });
    }

    public function getTeacherNameAttribute()
    {
        return $this->teacher()->pluck('first_name')->first();
    }

    public function getTeacherProfileImageAttribute()
    {
        return $this->teacher->getImageAttribute();
    }

    public function getCategoryNameAttribute()
    {
        return $this->category()->pluck('name')->first();
    }

    public function getIsFavoriteAttribute()
    {
        return Auth::user()->isInFavoritesList($this);
    }
    public function getProgressRatioAttribute()
    {
        return Auth::user();
    }


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    public function videos(): MorphMany
    {
        return $this->morphMany(Video::class, 'videoable');
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function Students(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'enrollments')
            ->withPivot([
                'is_favorite',
                'student_has_enrolled',
                'progress'
            ])
            ->where('student_has_enrolled', true);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    // scopes
    public static function scopeActive(Builder $query): void
    {
        $query->where('status', 'active');
    }


}
