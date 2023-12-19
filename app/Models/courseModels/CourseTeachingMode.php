<?php

namespace App\Models\courseModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Course;

class CourseTeachingMode extends Model
{
    use HasFactory;
    protected $fillable = ['course_id',
    'percentage',
    'mode_of_instruction',
    'contact_hours'
];

    function course()
    {
        return $this->belongsTo(Course::class , 'course_id');
    }
}
