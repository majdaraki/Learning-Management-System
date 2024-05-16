<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    // teacher relations
    // ________________________________
    public function media(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediable');
    }

    public function Categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'specializations');
    }
    // ________________________________





    //student relations
    // ________________________________
    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'answers');
    }

    public function choices(): BelongsToMany
    {
        return $this->belongsToMany(Choice::class, 'answers',relatedPivotKey:'chosen_choice_id');
    }


    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'enrollments')
            ->withPivotValue(['is_favorite']);
    }

    // ________________________________


}
