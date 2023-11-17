<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Department;

class Center extends Model
{
    use HasFactory, SoftDeletes;
    public function departments(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    protected $fillable = [
        'name',
        'department_id',
        'description',
    ];
}
