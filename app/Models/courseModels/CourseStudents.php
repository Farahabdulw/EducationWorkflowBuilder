<?php

namespace App\Models\courseModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;

class CourseStudents extends Model
{
    use HasFactory;
    protected $fillable = ['course_id' , 'name' , 'std_id'];

    function course()
    {
        return $this->belongsTo(Course::class , 'course_id');
    }
}
