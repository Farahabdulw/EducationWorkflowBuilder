<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Forms;
class Category extends Model
{
    use HasFactory , SoftDeletes;
    public function forms()
    {
        return $this->belongsToMany(Forms::class, 'category_form');
    }
    protected $fillable = [
        'name',
        'description',
    ];
}
