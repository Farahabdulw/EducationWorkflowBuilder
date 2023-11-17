<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Committe;
use App\Models\Department;
use App\Models\Groups;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable ,SoftDeletes , HasRoles;
    
    
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
        'birthdate',
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
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function committe() : HasMany
    {
        return $this->hasMany(Committe::class);
    }
    public function department(): HasOne
    {
        return $this->hasOne(Department::class, 'chairperson', 'id');
    }
    public function groups() : BelongsToMany
    {
        return $this->belongsToMany(Groups::class, 'groups-users');
    }
}
