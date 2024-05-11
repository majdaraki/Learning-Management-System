<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Specialization extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
    ];


    public function Category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function Teacher(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
