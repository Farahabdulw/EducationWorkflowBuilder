<?php

namespace App\Models\courseModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Course;

class CourseContactHours extends Model
{
    use HasFactory;
    protected $fillable = ['course_id' , 'activity
    ','hours' ];

    function course()
    {
        return $this->belongsTo(Course::class , 'course_id');
    }
}
