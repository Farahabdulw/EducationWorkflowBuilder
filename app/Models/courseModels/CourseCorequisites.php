<?php

namespace App\Models\courseModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Course;

class CourseCorequisites extends Model
{
    use HasFactory;
    protected $fillable = ['course_id' , 'name'];

    function course()
    {
        return $this->belongsTo(Course::class , 'course_id');
    }
}
