<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;
class Groups extends Model
{
    use HasFactory ,SoftDeletes;
    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'groups-users');
    }
    protected $fillable = [
        'name',
        'affiliations',
        'permissions',
    ];
}
