<?php

namespace App\Models\courseModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Course;

class CourseAssessmentQuality extends Model
{
    use HasFactory;
    protected $fillable = ['course_id' ,'assessment_area'
    ,"assessor"
    ,'assessment_method'];
    function course()
    {
        return $this->belongsTo(Course::class , 'course_id');
    }
}
