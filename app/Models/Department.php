<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\College;
use App\Models\User;

class Department extends Model
{
    use HasFactory,SoftDeletes;
    public function colleges():BelongsTo
    {
        return $this->belongsTo(College::class,"college_id");
    }
    public function chairperson():BelongsTo
    {
        return $this->belongsTo(User::class,"chairperson" , "id");
    }
    public function centers():HasMany
    {
        return $this->hasMany(Center::class);
    }
    protected $fillable = [
        'name',
        'college_id',
        'chairperson',
        'description',
    ];
}
