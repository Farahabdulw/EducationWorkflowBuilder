<?php

namespace App\Models\courseModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Course;

class CourseFacilitiesAndEquipment extends Model
{
    use HasFactory;
    function course()
    {
        return $this->belongsTo(Course::class , 'course_id');
    }
}
