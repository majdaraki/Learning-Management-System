<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'email_verified_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $appends = [
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


    public function getCreatedFromAttribute()
    {
        return $this->created_at->diffForHumans();
    }


    // Relations
    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }


    // teacher relations and methods
    // ________________________________

    public function Categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'specializations');
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    // ________________________________





    //student relations and methods
    // ________________________________
    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'answers');
    }

    public function choices(): BelongsToMany
    {
        return $this->belongsToMany(Choice::class, 'answers', relatedPivotKey: 'chosen_choice_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function coursesEnrollments(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'enrollments')
            ->withPivot([
                'is_favorite',
                'student_has_enrolled',
                'progress'
            ])
            ->where('student_has_enrolled', true);
    }

    public function favoriteCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'enrollments')
            ->withPivot([
                'is_favorite',
                'student_has_enrolled',
                'progress',
            ])
            ->where('is_favorite', true);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    public function isEnrolledInCourse(Course $course): bool
    {
        return $this->coursesEnrollments->contains($course);
    }

    public function isInFavoritesList(Course $course): bool
    {
        return $this->favoriteCourses->contains($course);
    }

    public function getGrade($quiz): float|null
    {
        return $this->results()
            ->where('quiz_id', $quiz->id)
            ->pluck('grade')->first();

    }

    // ________________________________


}
