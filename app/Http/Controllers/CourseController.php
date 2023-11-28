<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Course;

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

    public function add_course(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'code' => 'required',
            'department' => 'required',
            'description' => 'max:255',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new course record in the database
        $course = Course::create([
            'title' => $request->get('title'),
            'department_id' => $request->get('department'),
            'code' => $request->get('code'),
        ]);

        // Return a success response
        if ($course)
            return response()->json(['success' => true, 'message' => 'Course added successfully'], 200);
        else
            return response()->json([
                'success' => false,
                'error' => $course,
            ], 422);
    }
    public function get_courses()
    {
        if (auth()->user()->hasRole('super-admin')) {
            $canEdit = true;
            $canDelete = true;
            $canAdd = true;
            $courses = Course::with('department')->get();
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
            $courses = Course::whereIn('id', $groupsAff->toArray())->with([
                'departments' => function ($query) {
                    $query->select('id', 'name');
                }
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
    public function edit_course(Request $request)
    {
        $course = Course::with('department')->find($request->id);

        if (!$course)
            return response()->json(['error' => 'course not found'], 404);

        $course->title = $request->title;
        $course->department_id = $request->department_id;
        $course->code = $request->code;
        $course->save();

        return response()->json(['message' => 'Course updated successfully', 'course' => $course], 200);
    }
}
