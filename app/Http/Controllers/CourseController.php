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
use Carbon\Carbon;
use ZipArchive;

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
                'department',
                'college',
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
            ])->find($id);

            return view('content.pages.courses.edit', compact('course'));
        }
        return view('403');

    }

    public function course(int $id)
    {
        if (auth()->user()->can('edit_courses') || auth()->user()->hasRole('super-admin')) {
            $course = Course::select('department_id')->find($id);

        }
        return response()->json($course, 200);
    }
    public function view_course($id)
    {
        $course = Course::with([
            'department',
            'college',
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
        ])->find($id);
        // dd('course', $course);
        return view('content.pages.courses.view', compact('course'));
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

                'essential_references' => $request->get('essentialReferences') ?? null,
                'supportive_references' => $request->get('supportiveReferences') ?? null,
                'electronic_references' => $request->get('electronicMaterials') ?? null,
                'other_references' => $request->get('otherLearningMaterials') ?? null,
                'version' => "1",
                'last_revision' => json_encode($lastRevisionData),
            ]);

            if ($course) {
                $this->createRecords($request, $course);
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Course added successfully', "course" => $course], 200);
            } else {
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
    public function mapping(Request $request)
    {
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
        $course->version = $course->version - -1;
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
    public function deleteExistingRecords($id)
    {
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
    public function createRecords($request, $course)
    {

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
                    'department',
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

        // $maxClosLengths = array_fill_keys(range(1, 7), 1);

        // foreach ($courses as $course) {
        //     dd($course->plos);
        //     if ($course->plos && is_array($course->plos)) {
        //         foreach ($course->plos as $plo) {
        //             if (isset($plo->clos) && is_array($plo->clos)) {
        //                 $maxClosLengths[$plo->id] = max(
        //                     isset($maxClosLengths[$plo->id]) ? $maxClosLengths[$plo->id] : 0,
        //                     count($plo->clos)
        //                 );
        //             }
        //         }
        //     }
        // }


        // $filePath = 'exports/courses.xlsx';
        // Excel::store(new MappingExport($courses, $maxClosLengths), $filePath);

        // $fileUrl = Storage::url($filePath);

        $responseObject = [
            'courses' => $courses,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete,
            'canAdd' => $canAdd,
            // 'file_url' => $fileUrl
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
    public function teachingMode_suggestions(Request $request)
    {
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
    public function contactHours_suggestions(Request $request)
    {
        $query = $request->input('q');
        list($entity, $searchTerm) = explode('~*~', $query);
        $courses = Course::whereHas("contactHours", function ($queryBuilder) use ($searchTerm, $entity) {
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
    public function framework_suggestions(Request $request, $section)
    {
        $query = $request->input('q');
        list($entity, $searchTerm) = explode('~*~', $query);
        $courses = Course::whereHas($section, function ($queryBuilder) use ($searchTerm, $entity) {
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
    public function register(Request $request)
    {

        $students = $request->get('students');
        foreach ($students as $students) {
            CourseStudents::create([
                'course_id' => $request->get('course'),
                'std_id' => $students['std_id'],
                'name' => $students['name'],
            ]);
        }
        return response()->json(['success' => true, 'message' => 'Course added successfully'], 200);
    }


    public $course;
    public function export_course($id)
    {

        $this->course = Course::with([
            'department',
            'college',
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
        ])->find($id);

        if (!$this->course)
            return view('404');

        $zip = new ZipArchive;
        $loadedDocPath = storage_path('app/private/course-template.docx');
        $newDocPath = storage_path('app/private/coursezip.docx');

        if ($zip->open($loadedDocPath) === true) {
            $content = $zip->getFromName('word/document.xml');
            $content = $this->PopulateFirstPage($content);
            $content = $this->PopulateSecondPage($content);

            $newZip = new ZipArchive;
            $newZip->open($newDocPath, ZipArchive::CREATE);

            // Add the modified content to the new document
            $newZip->addFromString('word/document.xml', $content);

            // Copy other files from the original document to the new one
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                if ($filename !== 'word/document.xml') {
                    $newZip->addFromString($filename, $zip->getFromIndex($i));
                }
            }

            $newZip->close();
            $zip->close();

            return response()->download($newDocPath, 'coursezip.docx');
        } else {
            // Handle error opening the Zip archive
            return response()->json(['error' => 'Failed to open the Word document.']);
        }
    }

    private function PopulateFirstPage($content)
    {
        $lastRevisionDate = Carbon::parse(json_decode($this->course->last_revision)->date)->format('Y/m/d');
        $newContent = str_replace('Enter Course Title', $this->course->title, $content);
        $newContent = str_replace('Enter Course Code', $this->course->code, $newContent);
        $newContent = str_replace('Enter Program Name', $this->course->program, $newContent);
        $newContent = str_replace('Enter Department Name', $this->course->department->name, $newContent);
        $newContent = str_replace('Enter College Name', $this->course->college->name, $newContent);
        $newContent = str_replace('Enter Institution Name', $this->course->institution, $newContent);
        $newContent = str_replace('Course Specification Version Number', $this->course->version, $newContent);
        $newContent = str_replace('Pick Revision Date', $lastRevisionDate, $newContent);
        return $newContent;
    }
    private function PopulateSecondPage($content)
    {
        $type = json_decode($this->course->type);

        $searchUniversity = '/☒(.*?University)/s';
        $typeValueUniversity = ($type->university === "true") ? '☐' : '☒';
        $content = preg_replace_callback($searchUniversity, function ($matches) use ($typeValueUniversity) {
            return $typeValueUniversity . $matches[1];
        }, $content, 1);

        $searchCollege = '/☐(.*?College)/s';
        $typeValueCollege = ($type->college === "true") ? '☐' : '☒';
        $content = preg_replace_callback($searchCollege, function ($matches) use ($typeValueCollege) {
            return $typeValueCollege . $matches[1];
        }, $content, 1);

        $searchDepartment = '/☒(.*?Department)/s';
        $typeValueDepartment = ($type->department === "true") ? '☐' : '☒';
        $content = preg_replace_callback($searchDepartment, function ($matches) use ($typeValueDepartment) {
            return $typeValueDepartment . $matches[1];
        }, $content, 1);

        $searchTrack = '/☐(.*?Track)/s';
        $typeValueTrack = ($type->track === "true") ? '☐' : '☒';
        $content = preg_replace_callback($searchTrack, function ($matches) use ($typeValueTrack) {
            return $typeValueTrack . $matches[1];
        }, $content, 1);

        $searchOthers = '/☐(.*?Others)/s';
        $typeValueOthers = ($type->others === "true") ? '☐' : '☒';
        $content = preg_replace_callback($searchOthers, function ($matches) use ($typeValueOthers) {
            return $typeValueOthers . $matches[1];
        }, $content, 1);

        $enrollment = json_decode($this->course->enrollment);
        $checkCategory = $enrollment->required ? 'Required' : 'Elective';
        $content = preg_replace('/☐(.*?' . $checkCategory . ')/s', '☒$1', $content, 1);

        $content = preg_replace('/(Credit hours:.*?\()([^)]+)(\))/s', 'Credit hours: ' . $this->course->credit, $content, 1);

        $content = preg_replace('/year at which this course is offered:(.*?\(.*?\))/s', 'year at which this course is offered: ' . $this->course->level, $content, 1);

        $content = str_replace('$description', $this->course->description, $content);

        $content = $this->replaceRequirements($content, '$pre-requirements', $this->course->preRequisites);
        $content = $this->replaceRequirements($content, '$co-requisites', $this->course->coRequisites);
        $content = $this->replaceRequirements($content, '$main-objective', $this->course->mainObjective);
        $content = $this->addRowsTeachingModes($content, $this->course->teachingMode);
        $content = $this->addRowsContactHours($content, $this->course->contactHours);
        $content = $this->addRowsInstruction($content, $this->course->knowledge, 1);
        $content = $this->addRowsInstruction($content, $this->course->skills, 2);
        $content = $this->addRowsInstruction($content, $this->course->values, 3);
        return $content;
    }
    private function addRowsTeachingModes($content, $modes)
    {

        $appendTo = '<w:t>Percentage</w:t></w:r></w:p></w:tc></w:tr>';
        $position = strpos($content, $appendTo);
        $rows = '';
        foreach ($modes as $index => $mode) {
            $row = '<w:tr w:rsidR="00B97745" w:rsidRPr="003B6DF4" w14:paraId="1171117D" w14:textId="77777777" w:rsidTr="00DB0E18"><w:trPr><w:trHeight w:val="260"/><w:tblCellSpacing w:w="7" w:type="dxa"/><w:jc w:val="center"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="846" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/><w:vAlign w:val="center"/></w:tcPr><w:p w14:paraId="5280797A" w14:textId="7D15F51F" w:rsidR="00B97745" w:rsidRPr="00DB0E18" w:rsidRDefault="00DB0E18" w:rsidP="00237363"><w:pPr><w:spacing w:after="0"/><w:ind w:left="300"/><w:jc w:val="center"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr><w:t>' .
                $index .
                '</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="3509" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/></w:tcPr><w:p w14:paraId="25C11AD5" w14:textId="61A8558A" w:rsidR="00B97745" w:rsidRPr="00DB0E18" w:rsidRDefault="00B97745" w:rsidP="00237363"><w:pPr><w:spacing w:after="0"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:sz w:val="28"/><w:szCs w:val="28"/><w:rtl/></w:rPr></w:pPr><w:r w:rsidRPr="00DB0E18"><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:sz w:val="28"/><w:szCs w:val="28"/></w:rPr><w:t>' . $mode->mode_of_instruction . '</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2607" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/><w:vAlign w:val="center"/></w:tcPr><w:p w14:paraId="7C2F2B3A" w14:textId="3E90745B" w:rsidR="00B97745" w:rsidRPr="003B6DF4" w:rsidRDefault="005C11FA" w:rsidP="00237363"><w:pPr><w:spacing w:after="0"/><w:jc w:val="center"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr><w:t>' . $mode->contact_hours . '</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2600" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/><w:vAlign w:val="center"/></w:tcPr><w:p w14:paraId="389A45B5" w14:textId="71BDF232" w:rsidR="00B97745" w:rsidRPr="003B6DF4" w:rsidRDefault="005C11FA" w:rsidP="00237363"><w:pPr><w:spacing w:after="0"/><w:jc w:val="center"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:cstheme="minorHAnsi"/></w:rPr><w:t>' . $mode->percentage . '</w:t></w:r></w:p></w:tc></w:tr>';
            $rows .= $row;

        }

        $part1 = substr($content, 0, $position + strlen($appendTo));
        $part2 = substr($content, $position + strlen($appendTo));

        // Concatenate the new rows in between
        $content = $part1 . $rows . $part2;
        return $content;
    }

    private function addRowsContactHours($content, $hours)
    {
        $appendTo = '<w:t>Contact Hours</w:t></w:r></w:p></w:tc></w:tr>';
        $position = strpos($content, $appendTo);
        $rows = '';
        $totalHours = 0;
        foreach ($hours as $index => $hours) {
            $row = '<w:tr w:rsidR="008D0CEB" w:rsidRPr="003B6DF4" w14:paraId="5D8E1F98" w14:textId="77777777" w:rsidTr="0068281D"><w:trPr><w:tblCellSpacing w:w="7" w:type="dxa"/><w:jc w:val="center"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="841" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/><w:vAlign w:val="center"/></w:tcPr><w:p w14:paraId="364B369B" w14:textId="676922FF" w:rsidR="008D0CEB" w:rsidRPr="0068281D" w:rsidRDefault="0068281D" w:rsidP="0068281D"><w:pPr><w:ind w:left="300"/><w:jc w:val="center"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:sz w:val="24"/><w:szCs w:val="24"/><w:rtl/><w:lang w:bidi="ar-EG"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:sz w:val="24"/><w:szCs w:val="24"/><w:lang w:bidi="ar-EG"/></w:rPr><w:t>' . $index . '</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="6734" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/></w:tcPr><w:p w14:paraId="7E7AAFBF" w14:textId="1BBF17E0" w:rsidR="008D0CEB" w:rsidRPr="00DB0E18" w:rsidRDefault="0068281D" w:rsidP="00237363"><w:pPr><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/><w:rtl/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr><w:t>' . $hours->activity . '</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2022" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/><w:vAlign w:val="center"/></w:tcPr><w:p w14:paraId="43B5319D" w14:textId="1F7A87A2" w:rsidR="008D0CEB" w:rsidRPr="003B6DF4" w:rsidRDefault="0068281D" w:rsidP="00237363"><w:pPr><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:color w:val="525252" w:themeColor="accent3" w:themeShade="80"/><w:sz w:val="24"/><w:szCs w:val="24"/><w:rtl/><w:lang w:bidi="ar-EG"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:color w:val="525252" w:themeColor="accent3" w:themeShade="80"/><w:sz w:val="24"/><w:szCs w:val="24"/><w:lang w:bidi="ar-EG"/></w:rPr><w:t>' . $hours->hours . '</w:t></w:r></w:p></w:tc></w:tr>';
            $totalHours += $this->getAmount($hours->hours);

            $rows .= $row;
        }
        $part1 = substr($content, 0, $position + strlen($appendTo));
        $part2 = substr($content, $position + strlen($appendTo));

        $content = $part1 . $rows . $part2;
        $content = str_replace('$total_contactHours', $totalHours, $content);
        return $content;
    }
    private function addRowsInstruction($content, $instructions, $preIndex)
    {
        $appendTo = '';

        switch ($preIndex) {
            case 1:
                $appendTo = '<w:t>Knowledge and understanding</w:t></w:r></w:p></w:tc></w:tr>';
                break;
            case 2:
                $appendTo = '<w:t>Skills</w:t></w:r></w:p></w:tc></w:tr>';
                break;
            case 3:
                $appendTo = '<w:t xml:space="preserve"> and responsibility</w:t></w:r></w:p></w:tc></w:tr>';
                break;
        }
        $position = strpos($content, $appendTo);
        $rows = '';
        foreach ($instructions as $index => $plo) {
            $row = '<w:tr w:rsidR="006E3A65" w:rsidRPr="003B6DF4" w14:paraId="2E821FBF" w14:textId="77777777" w:rsidTr="00067A97"><w:trPr><w:tblCellSpacing w:w="7" w:type="dxa"/><w:jc w:val="center"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="901" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/><w:vAlign w:val="center"/></w:tcPr><w:p w14:paraId="20103120" w14:textId="77777777" w:rsidR="006E3A65" w:rsidRPr="003B6DF4" w:rsidRDefault="006E3A65" w:rsidP="00237363"><w:pPr><w:spacing w:after="0"/><w:jc w:val="center"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:color w:val="525252" w:themeColor="accent3" w:themeShade="80"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:r w:rsidRPr="003B6DF4"><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:color w:val="525252" w:themeColor="accent3" w:themeShade="80"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr><w:t>' . ($preIndex . '.' . ($index + 1)) . '</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2322" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/><w:vAlign w:val="center"/></w:tcPr><w:p w14:paraId="28395DEF" w14:textId="324785E5" w:rsidR="006E3A65" w:rsidRPr="003B0CC7" w:rsidRDefault="003B0CC7" w:rsidP="00237363"><w:pPr><w:spacing w:after="0"/><w:jc w:val="lowKashida"/></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:t>' . ($plo->learning_outcome ?? "empty") . '</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2481" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/></w:tcPr><w:p w14:paraId="3BD57457" w14:textId="5B453D2D" w:rsidR="006E3A65" w:rsidRPr="003B6DF4" w:rsidRDefault="003B0CC7" w:rsidP="00237363"><w:pPr><w:spacing w:after="0"/><w:jc w:val="lowKashida"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:t>' . ($plo->CLO_code ?? "empty") . '</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2087" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/><w:vAlign w:val="center"/></w:tcPr><w:p w14:paraId="06DCF145" w14:textId="5BC6F84E" w:rsidR="006E3A65" w:rsidRPr="003B6DF4" w:rsidRDefault="003B0CC7" w:rsidP="00237363"><w:pPr><w:spacing w:after="0"/><w:jc w:val="lowKashida"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:t>' . ($plo->teaching_strategies ?? "empty") . '</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1757" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/><w:vAlign w:val="center"/></w:tcPr><w:p w14:paraId="6520985D" w14:textId="3992E0CF" w:rsidR="006E3A65" w:rsidRPr="003B6DF4" w:rsidRDefault="003B0CC7" w:rsidP="00237363"><w:pPr><w:spacing w:after="0"/><w:jc w:val="lowKashida"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:t>' . ($plo->assessment_methods ?? "empty") . '</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc></w:tr>';
            $rows .= $row;
        }
        $part1 = substr($content, 0, $position + strlen($appendTo));
        $part2 = substr($content, $position + strlen($appendTo));

        $content = $part1 . $rows . $part2;
        return $content;
    }
    private function replaceRequirements($content, $placeholder, $requirements)
    {
        $replacement = '';

        foreach ($requirements as $index => $requirement) {
            $replacement .= $index + 1 . "-" . $requirement->name . "<w:br/>";
        }

        return str_replace($placeholder, $replacement, $content);
    }
    function getAmount($hoursString)
    {
        $parts = explode('-', $hoursString);

        if (count($parts) !== 2) {
            // Incorrect format, return 0
            return 0;
        }

        list($start, $end) = $parts;

        $startComponents = explode(':', $start);
        $endComponents = explode(':', $end);

        if (count($startComponents) !== 2 || count($endComponents) !== 2) {
            // Incorrect time format, return 0
            return 0;
        }

        list($startHour, $startMinute) = $startComponents;
        list($endHour, $endMinute) = $endComponents;

        if (
            !ctype_digit($startHour) || !ctype_digit($startMinute) ||
            !ctype_digit($endHour) || !ctype_digit($endMinute)
        ) {
            // Invalid numeric values, return 0
            return 0;
        }

        $startTime = $startHour * 60 + $startMinute;
        $endTime = $endHour * 60 + $endMinute;

        if ($startTime > $endTime) {
            // End time is before start time, return 0
            return 0;
        }

        $amount = ($endTime - $startTime) / 60;

        return $amount;
    }

}
