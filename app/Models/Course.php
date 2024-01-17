<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Department;
use App\Models\College;
use App\Models\courseModels\CoursePrerequisites;
use App\Models\courseModels\CourseCorequisites;
use App\Models\courseModels\CourseMainObjective;
use App\Models\courseModels\CourseTeachingMode;
use App\Models\courseModels\CourseContactHours;
use App\Models\courseModels\CourseKnowledge;
use App\Models\courseModels\CourseSkills;
use App\Models\courseModels\CourseValues;
use App\Models\courseModels\CourseContent;
use App\Models\courseModels\CourseStudentsAssessment;
use App\Models\courseModels\CourseFacilitiesAndEquipment;
use App\Models\courseModels\CourseAssessmentQuality;
use App\Models\courseModels\CourseStudents;


class Course extends Model
{
    use HasFactory, SoftDeletes;
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class, 'college_id');
    }
    protected $fillable = [
        'title',
        'department_id',
        'code',
        'program',
        'college_id',
        'institution',
        'credit',
        'tatorial',
        'description',
        'approved_by',
        'approval_number',
        'approval_date',
        'type',
        'level',
        'enrollment',
        'version',
        'last_revision',
        'essential_references',
        'supportive_references',
        'electronic_references',
        'other_references',
    ];

    public function preRequisites()
    {
        return $this->hasMany(CoursePrerequisites::class);
    }
    public function coRequisites()
    {
        return $this->hasMany(CourseCorequisites::class);
    }
    public function mainObjective()
    {
        return $this->hasMany(CourseMainObjective::class);
    }
    public function teachingMode()
    {
        return $this->hasMany(CourseTeachingMode::class);
    }
    public function contactHours()
    {
        return $this->hasMany(CourseContactHours::class);
    }
    public function knowledge()
    {
        return $this->hasMany(CourseKnowledge::class);
    }
    public function skills()
    {
        return $this->hasMany(CourseSkills::class);
    }
    public function values()
    {
        return $this->hasMany(CourseValues::class);
    }
    public function content()
    {
        return $this->hasMany(CourseContent::class);
    }
    public function studentsAssessment()
    {
        return $this->hasMany(CourseStudentsAssessment::class);
    }
    public function facilitiesAndEquipment()
    {
        return $this->hasMany(CourseFacilitiesAndEquipment::class);
    }
    public function assessmentQuality()
    {
        return $this->hasMany(CourseAssessmentQuality::class);
    }
    public function students()
    {
        return $this->hasMany(CourseStudents::class);
    }
}
