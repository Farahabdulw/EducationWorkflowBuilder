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
        'form_id',
    ];
    public function form()
    {
        return $this->belongsTo(Forms::class);
    }

    public function steps()
    {
        return $this->hasMany(Step::class, 'workflow_id', 'id');
    }
}
