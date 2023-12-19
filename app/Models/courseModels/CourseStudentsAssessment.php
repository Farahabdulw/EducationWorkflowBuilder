<?php

namespace App\Models\courseModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Course;

class CourseStudentsAssessment extends Model
{
    use HasFactory;
    protected $fillable = ['course_id' , 
    'assessment_activity',
    'assessment_timing',
    'percentage'];

    function course()
    {
        return $this->belongsTo(Course::class , 'course_id');
    }
}
