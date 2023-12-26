<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Course;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MappingExport;


class CourseController extends Controller
{
    public function index()
    {
        return view('content.pages.courses.index');
    }
    public function create()
    {
        return view('content.pages.courses.create');
    }
    public function register_portal()
    {
        return view('content.pages.courses.register');
    }

    public function edit(int $id)
    {
        if (auth()->user()->can('edit_courses') || auth()->user()->hasRole('super-admin')) {
            $course = Course::with([
                'department' ,
                'preRequisites' ,
                'coRequisites',
                'mainObjective',
                'teachingMode',
                'contactHours',
                'knowledge',
                'skills',
                'values',
                'content',
                'studentsAssessment',
                'facilitiesAndEquipment',
                'assessmentQuality',
                'students'
            ])->find($id);

            return view('content.pages.courses.edit', compact('course'));
        }
        return view('403');

    }
    
    public function course(int $id)
    {
        if (auth()->user()->can('edit_courses') || auth()->user()->hasRole('super-admin')) {
            $course = Course::select( 'department_id')->find($id);

        }
        return response()->json($course, 200);
    }
    public function add_course(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'code' => 'required',
            'department' => 'required',
            'college' => 'required',
            'program' => 'required',
            'institution' => 'required',
            'creditHours' => 'required|numeric',
            'description' => 'max:1000',
            'councilOrCommitte' => 'required',
            'referenceNumber' => 'required',
            'level' => 'required',
            'date' => 'required|date',
            'councilOrCommitte' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        $currentUser = auth()->user();
        try {
            DB::beginTransaction();
        
            $lastRevisionData = [
                'date' => now(), 
                'by' => $currentUser->first_name . ' ' . $currentUser->last_name,
            ];

            $course = Course::create([
                'title' => $request->get('title'),
                'code' => $request->get('code'),
                'program' => $request->get('program'),
                'department_id' => $request->get('department'),
                'college_id' => $request->get('college'),
                'institution' => $request->get('institution'),

                'credit' => $request->get('creditHours'),
                'tatorial' => $request->get('tatorialHours'),
                'description' => $request->get('description') ?? " ",

                'approved_by' => $request->get('councilOrCommitte'),
                'approval_number' => $request->get('referenceNumber'),
                'approval_date' => $request->get('date'),
                'level' => $request->get('level'),

                'type' => json_encode($request->get('courseCategories') ?? []),
                'enrollment' => json_encode($request->get('enrollmentOption') ?? []),

                'essential_references' => $request->get('essentialReferences') ?? null ,
                'supportive_references' => $request->get('supportiveReferences') ?? null ,
                'electronic_references' => $request->get('electronicMaterials') ?? null ,
                'other_references' => $request->get('otherLearningMaterials') ?? null ,
                'version' => "1" ,
                'last_revision' => json_encode($lastRevisionData),
            ]);

            if ($course){
                $this->createRecords($request , $course);
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Course added successfully' , "course"=> $course], 200);
            }else{
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'error' => $course,
                ], 422);
            }
        } catch (\Exception $e) {
            DB::rollBack();
        
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
    public function mapping(Request $request){
        return view('content.pages.courses.mapping');
    }
   

    public function export(Request $request)
    {
        $courses = $request->courses;
        $maxClosLengths = array_fill_keys(range(1, 7), 1);

        foreach ($courses as $course) {
            foreach ($course["plos"] as $plo) {
                if (isset($plo["clos"])) {
                    $maxClosLengths[$plo["id"]] = max(
                        isset($maxClosLengths[$plo["id"]]) ? $maxClosLengths[$plo["id"]] : 0,
                        count($plo["clos"])
                    );
                }
            }
        }
        // dd($courses);

        $filePath = 'exports/courses.xlsx';
        Excel::store(new MappingExport($courses, $maxClosLengths), $filePath);

        $fileUrl = Storage::url($filePath);

        return response()->json(['file_url' => $fileUrl]);
    }

    public function downloadCourses()
    {
        $filePath = 'exports/courses.xlsx';

        $file = storage_path('app/' . $filePath);

        return new BinaryFileResponse($file, 200, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="master mapping sheet.xlsx"',
        ]);
    }
// public function import(Request $request){
    //     if ($request->hasFile('fileToImport')) {
    //         $file = $request->file('fileToImport');
    //         $validFileTypes = ['xlsx', 'xls'];
    //         $extension = strtolower($file->getClientOriginalExtension());

    //         if (!in_array($extension, $validFileTypes)) 
    //             return response()->json(['error' => 'Invalid file type. Only Excel files are accepted.'], 400);
            
    //         $spreadsheet = IOFactory::load($file->getPathname());

    //         // Get all sheets in the workbook
    //         $allSheets = $spreadsheet->getAllSheets();
    
    //         // Skip the first sheet and process the rest
    //         foreach ($allSheets as $index => $sheet) {
    //             if ($index === 0) continue;
                
    //             $this->processSheet($sheet);
    //         }
    
    //         return response()->json(['success' => 'File uploaded and processed successfully.']);
            
    //     }

    //     return response()->json(['error' => 'No file was uploaded.'], 400);
    // }

    // public function processSheet($sheet){
    //     $PLOSTable = [ "C"=>'Knowledge' , "D"=>'skills' , "E"=> 'skills' , "F"=> 'values' ,"G"=> 'values' , "H"=>'skills'];
    //     $courseInfo =[];
    //     $courseInfo[]->code = $sheet->cell('B1')
    //     $courseInfo[]->title = $sheet->cell('B2')
    //     $courseInfo[]->offeredTo = $sheet->cell('B3')
    //     $courseInfo[]->group = $sheet->cell('B4')
    //     $courseInfo[]->knowledge[] = $sheet->cell('B$')

    // }   
    public function update_course(Request $request)
    {
        $course = Course::with('department')->find($request->id);

        if (!$course)
            return response()->json(['error' => 'course not found'], 404);

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'code' => 'required',
            'department' => 'required',
            'college' => 'required',
            'program' => 'required',
            'institution' => 'required',
            'creditHours' => 'required|numeric',
            'description' => 'max:1000',
            'councilOrCommitte' => 'required',
            'referenceNumber' => 'required',
            'level' => 'required',
            'date' => 'required|date',
            'councilOrCommitte' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        $currentUser = auth()->user();
        $course->version = $course->version - - 1; 
        $lastRevisionData = [
            'date' => now(), 
            'by' => $currentUser->first_name . ' ' . $currentUser->last_name,
        ];
        
        $course->title = $request->title;
        $course->code = $request->code;
        $course->program = $request->program;
        $course->department_id = $request->department;
        $course->college_id = $request->college;
        $course->institution = $request->institution;
        
        $course->credit = $request->creditHours;
        $course->tatorial = $request->tatorialHours;
        $course->description = $request->description ?? " ";
        $course->approved_by = $request->councilOrCommitte;
        $course->approval_number = $request->referenceNumber;
        $course->approval_date = $request->date;
        $course->level = $request->level;

        $course->type = json_encode($request->courseCategories ?? []);
        $course->enrollment = json_encode($request->enrollmentOption ?? []);

        $course->essential_references = $request->essentialReferences;
        $course->supportive_references = $request->supportiveReferences;
        $course->electronic_references = $request->electronicMaterials;
        $course->other_references = $request->otherLearningMaterials;

        $this->deleteExistingRecords($course->id);
        $this->createRecords($request, $course);
        $course->save();

        return response()->json(['message' => 'Course updated successfully', 'course' => $course], 200);
    }
    public function deleteExistingRecords($id){
        CoursePrerequisites::where('course_id', $id)->delete();
        CourseCorequisites::where('course_id', $id)->delete();
        CourseMainObjective::where('course_id', $id)->delete();
        CourseTeachingMode::where('course_id', $id)->delete();
        CourseContactHours::where('course_id', $id)->delete();
        CourseKnowledge::where('course_id', $id)->delete();
        CourseSkills::where('course_id', $id)->delete();
        CourseValues::where('course_id', $id)->delete();
        CourseContent::where('course_id', $id)->delete();
        CourseStudentsAssessment::where('course_id', $id)->delete();
        CourseFacilitiesAndEquipment::where('course_id', $id)->delete();
        CourseAssessmentQuality::where('course_id', $id)->delete();
    }
    public function createRecords($request , $course){

        if ($request->has('preRequirements')) {
            $preRequirements = $request->get('preRequirements');
            foreach ($preRequirements as $preRequirement) {
                CoursePrerequisites::create([
                    'course_id' => $course->id,
                    'name' => $preRequirement['PreRequirment'],
                ]);
            }
        }
        if ($request->has('coRequisites')) {
            $coRequisites = $request->get('coRequisites');
            foreach ($coRequisites as $coRequisite) {
                CourseCorequisites::create([
                    'course_id' => $course->id,
                    'name' => $coRequisite['CoRequirment'],
                ]);
            }
        }
        if ($request->has('courseMainObjectives')) {
            $courseMainObjectives = $request->get('courseMainObjectives');
            foreach ($courseMainObjectives as $courseMainObjective) {
                CourseMainObjective::create([
                    'course_id' => $course->id,
                    'name' => $courseMainObjective['mainObjective'],
                ]);
            }
        }
        if ($request->has('teachingModes')) {
            $teachingModes = $request->get('teachingModes');
            foreach ($teachingModes as $teachingMode) {
                CourseTeachingMode::create([
                    'course_id' => $course->id,
                    'percentage' => $teachingMode['percentage'],
                    'mode_of_instruction' => $teachingMode['modeOfInstruction'],
                    'contact_hours' => $teachingMode['contactHour'],
                ]);
            }
        }
        if ($request->has('contactHours')) {
            $contactHours = $request->get('contactHours');
            foreach ($contactHours as $contactHour) {
                CourseContactHours::create([
                    'course_id' => $course->id,
                    'activity' => $contactHour['activity'],
                    'hours' => $contactHour['contactHour'],
                ]);
            }
        }
        if ($request->has('knowledge')) {
            $knowledge = $request->get('knowledge');
            foreach ($knowledge as $knlg) {
                CourseKnowledge::create([
                    'course_id' => $course->id,
                    'learning_outcome' => $knlg['learningOutcomes'],
                    'CLO_code' => $knlg['codeCLOs'],
                    'teaching_strategies' => $knlg['teachingStrategies'],
                    'assessment_methods' => $knlg['assessmentMethods'],
                ]);
            }
        }
        if ($request->has('skills')) {
            $skills = $request->get('skills');
            foreach ($skills as $skill) {
                CourseSkills::create([
                    'course_id' => $course->id,
                    'learning_outcome' => $skill['learningOutcomes'],
                    'CLO_code' => $skill['codeCLOs'],
                    'teaching_strategies' => $skill['teachingStrategies'],
                    'assessment_methods' => $skill['assessmentMethods'],
                ]);
            }
        }
        if ($request->has('values')) {
            $values = $request->get('values');
            foreach ($values as $value) {
                CourseValues::create([
                    'course_id' => $course->id,
                    'learning_outcome' => $value['learningOutcomes'],
                    'CLO_code' => $value['codeCLOs'],
                    'teaching_strategies' => $value['teachingStrategies'],
                    'assessment_methods' => $value['assessmentMethods'],
                ]);
            }
        }
        if ($request->has('courseContents')) {
            $courseContents = $request->get('courseContents');
            foreach ($courseContents as $content) {
                CourseContent::create([
                    'course_id' => $course->id,
                    'topic' => $content['topic'],
                    'contact_hours' => $content['contactHour'],
                ]);
            }
        }
        if ($request->has('studentsAssessmentActivities')) {
            $studentsAssessmentActivities = $request->get('studentsAssessmentActivities');
            foreach ($studentsAssessmentActivities as $studentsAssessmentActivitie) {
                CourseStudentsAssessment::create([
                    'course_id' => $course->id,
                    'assessment_activity' => $studentsAssessmentActivitie['assessmentActivity'],
                    'assessment_timing' => $studentsAssessmentActivitie['assessmentTiming'],
                    'percentage' => $studentsAssessmentActivitie['assessmentpercentage'],
                ]);
            }
        }
        if ($request->has('facilitiesEquipments')) {
            $facilitiesEquipments = $request->get('facilitiesEquipments');
            foreach ($facilitiesEquipments as $facilitiesEquipment) {
                CourseFacilitiesAndEquipment::create([
                    'course_id' => $course->id,
                    'items' => $facilitiesEquipment['item'],
                    'resource' => $facilitiesEquipment['resource'],
                ]);
            }
        }
        if ($request->has('assessmentCourseQualitys')) {
            $assessmentCourseQualitys = $request->get('assessmentCourseQualitys');
            foreach ($assessmentCourseQualitys as $assessmentCourseQuality) {
                CourseAssessmentQuality::create([
                    'course_id' => $course->id,
                    'assessment_area' => $assessmentCourseQuality['assessmentArea'],
                    'assessor' => $assessmentCourseQuality['assessor'],
                    'assessment_method' => $assessmentCourseQuality['assessmentMethod'],
                ]);
            }
        }
    }
    public function get_courses()
    {
        if (auth()->user()->hasRole('super-admin')) {
            $canEdit = true;
            $canDelete = true;
            $canAdd = true;
            $courses = Course::with([
                'department',
                'preRequisites',
                'coRequisites',
                'mainObjective',
                'teachingMode',
                'contactHours',
                'knowledge',
                'skills',
                'values',
                'content',
                'studentsAssessment',
                'facilitiesAndEquipment',
                'assessmentQuality',
                'students'
            ])->get();
        } else {
            $authUser = auth()->user();
            $canEdit = auth()->user()->can('courses_edit');
            $canDelete = auth()->user()->can('courses_delete');
            $canAdd = auth()->user()->can('courses_add');

            $groupsAff = $authUser->groups->pluck('affiliations')->map(function ($affiliations) {
                $affiliationsArray = json_decode($affiliations, true);
                return $affiliationsArray['courses'] ?? [];
            })->flatten();

            // Assuming $courses is the collection of all courses
            $courses = Course::whereIn('id', $groupsAff->toArray())
                ->with([
                    'department' ,
                    'coRequisites',
                    'mainObjective',
                    'teachingMode',
                    'contactHours',
                    'knowledge',
                    'skills',
                    'values',
                    'content',
                    'studentsAssessment',
                    'facilitiesAndEquipment',
                    'assessmentQuality',
                    'students'
                ])->get();

        }
        $responseObject = [
            'courses' => $courses,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete,
            'canAdd' => $canAdd,
        ];
        return response()->json($responseObject, 200);
    }
    public function get_course($id)
    {
        $course = Course::with('department')->find($id);

        if (!$course) {
            return response()->json(['error' => 'course not found'], 404);
        }
        return response()->json($course, 200);
    }
    public function delete(Request $request)
    {
        $course = Course::find($request->id);

        if (!$course) {
            return response()->json(['error' => "Course not found $request->id "], 404);
        }
        $course->delete();

        return response()->json(['success' => true, 'message' => 'Course soft deleted successfully'], 200);
    }

    public function specification_suggestions(Request $request)
    {
        $query = $request->input('q');
        list($entity, $query) = explode('~*~', $query);
    
        $suggestions = Course::select($entity)
            ->where($entity, 'like', '%' . $query . '%')
            ->groupBy($entity)
            ->get(); 
    
        return response()->json($suggestions);
    }
    public function identification_suggestions(Request $request)
    {
        $query = $request->input('q');
        list($entity, $searchTerm) = explode('~*~', $query);
    
        $courses = Course::whereHas($entity, function ($queryBuilder) use ($searchTerm) {
            $queryBuilder->where('name', 'like', '%' . $searchTerm . '%');
        })
            ->get();
        $suggestions = [];
    
        foreach ($courses as $course) {
            $relatedEntities = $course->{$entity};
    
            if ($relatedEntities && $relatedEntities->isNotEmpty()) {
                foreach ($relatedEntities as $relatedEntity) {
                    $suggestions[] = [$entity => $relatedEntity->name];
                }
            }
        }
    
        return response()->json($suggestions);
    }
    public function teachingMode_suggestions(Request $request){
        $query = $request->input('q');
        list($entity, $searchTerm) = explode('~*~', $query);
        $courses = Course::whereHas("teachingMode", function ($queryBuilder) use ($searchTerm) {
            $queryBuilder->where('mode_of_instruction', 'like', '%' . $searchTerm . '%');
        })
            ->get();
        $suggestions = [];
    
        foreach ($courses as $course) {
            $relatedEntities = $course->teachingMode;
    
            if ($relatedEntities && $relatedEntities->isNotEmpty()) {
                foreach ($relatedEntities as $relatedEntity) {
                    $suggestions[] = [$entity => $relatedEntity->mode_of_instruction];
                }
            }
        }
        return response()->json($suggestions);
    }
    public function contactHours_suggestions(Request $request){
        $query = $request->input('q');
        list($entity, $searchTerm) = explode('~*~', $query);
        $courses = Course::whereHas("contactHours", function ($queryBuilder) use ($searchTerm , $entity) {
            $queryBuilder->where($entity, 'like', '%' . $searchTerm . '%');
        })
            ->get();
        $suggestions = [];
    
        foreach ($courses as $course) {
            $relatedEntities = $course->contactHours;
    
            if ($relatedEntities && $relatedEntities->isNotEmpty()) {
                foreach ($relatedEntities as $relatedEntity) {
                    $suggestions[] = [$entity => $relatedEntity->{$entity}];
                }
            }
        }
        return response()->json($suggestions);
    }
    public function framework_suggestions(Request $request , $section){
        $query = $request->input('q');
        list($entity, $searchTerm) = explode('~*~', $query);
        $courses = Course::whereHas($section, function ($queryBuilder) use ($searchTerm , $entity) {
            $queryBuilder->where($entity, 'like', '%' . $searchTerm . '%');
        })
            ->get();
        $suggestions = [];
    
        foreach ($courses as $course) {
            $relatedEntities = $course->{$section};
    
            if ($relatedEntities && $relatedEntities->isNotEmpty()) {
                foreach ($relatedEntities as $relatedEntity) {
                    $suggestions[] = [$entity => $relatedEntity->{$entity}];
                }
            }
        }
        return response()->json($suggestions);
    }
    public function register(Request $request){

        $students = $request->get('students');
        foreach ($students as $students) {
            CourseStudents::create([
                'course_id' => $request->get('course'),
                'std_id' => $students['std_id'],
                'name' => $students['name'],
            ]);
        }
        return response()->json(['success' => true, 'message' => 'Course added successfully' ], 200);
    }

}
