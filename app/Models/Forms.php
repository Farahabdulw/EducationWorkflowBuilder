<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Category;
use App\Models\Workflow;

class Forms extends Model
{
    use HasFactory, SoftDeletes;
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_form');
    }
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function workflows()
    {
        return $this->hasMany(Workflow::class);
    }
    protected $fillable = [
        'name',
        'file',
        'created_by',
        'content',
    ];
}
