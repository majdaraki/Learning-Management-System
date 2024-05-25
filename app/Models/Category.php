<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Category extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'parent_id',
    ];

    protected $appends = ['created_from', 'image'];

    public function getImageAttribute()
    {
        $image = $this->image()->pluck('name')->first();
        if (!$image) {
            return '';
        }
        return asset('storage/' . $image);
    }


    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }


    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'specializations');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function childrens()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * return only main categories.
     */
    public static function scopeParents(Builder $query): void
    {
        $query->where('parent_id', null);
    }

}
