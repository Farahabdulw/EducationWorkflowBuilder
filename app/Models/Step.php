<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Workflow;

class Step extends Model
{
    use HasFactory;
    protected $fillable = [
        'workflow_id',
        'user_id',
        'step',
        'isReturened',
        'status',
        'review',
        'forwarded_from',
    ];
    public function workflow()
    {
        return $this->belongsTo(Workflow::class, 'workflow_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
