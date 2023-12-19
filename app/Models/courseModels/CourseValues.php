<?php

namespace App\Models\courseModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Course;

class CourseValues extends Model
{
    use HasFactory;
    protected $fillable = ['course_id' , 
    "learning_outcome",
    'CLO_code',
    'teaching_strategies',
    'assessment_methods'];
    function course()
    {
        return $this->belongsTo(Course::class , 'course_id');
    }
}
