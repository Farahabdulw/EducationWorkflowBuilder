<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Step;
use App\Models\Forms;

class Workflow extends Model
{
    use HasFactory;

    protected $fillable = [
        'forms_id',
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
}
