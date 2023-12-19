<?php

namespace App\Models\courseModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Course;

class CourseContent extends Model
{
    use HasFactory;
    protected $fillable = ['course_id' ,'topic' ,'contact_hours'];

    function course()
    {
        return $this->belongsTo(Course::class , 'course_id');
    }
}
