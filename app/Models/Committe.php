<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\User;
use App\Models\College;

class Committe extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'chairperson',
        'description',
    ];
    protected $table = 'committes';
    public function chairpersonUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chairperson', 'id');
    }
    public function colleges() : HasMany
    {
        return $this->hasMany(College::class);
    }
}
