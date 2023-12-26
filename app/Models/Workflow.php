<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Step;
use App\Models\Forms;
use App\Models\User;

class Workflow extends Model
{
    use HasFactory;

    protected $fillable = [
        'forms_id',
        'created_by',
        'affiliations',
        'status',
    ];
    public function form()
    {
        return $this->belongsTo(Forms::class , 'forms_id');
    }

    public function steps()
    {
        return $this->hasMany(Step::class, 'workflow_id', 'id');
    }
    public function creator() : BelongsTo
    {
        return $this->belongsTo(User::class , 'created_by');
    }
}
