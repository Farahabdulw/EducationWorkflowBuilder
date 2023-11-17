<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Category;

class Forms extends Model
{
    use HasFactory, SoftDeletes;
    public function categories() : BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_form');
    }
    protected $fillable = [
        'name',
        'content',
    ];
}
