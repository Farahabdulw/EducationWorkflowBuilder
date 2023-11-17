<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    public function index()
    {
        return view('content.pages.departments.index');
    }
    public function create()
    {
        return view('content.pages.departments.create');
    }
    public function add_department(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'college' => 'required',
            'chairperson' => 'required',
            'description' => 'max:255',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new department record in the database
        $department = Department::create([
            'name' => $request->get('name'),
            'college_id' => $request->get('college'),
            'chairperson' => $request->get('chairperson'),
            'description' => $request->get('description'),
        ]);

        // Return a success response
        if ($department)
            return response()->json(['success' => true, 'message' => 'Department been added successfully'], 200);
        else
            return response()->json([
                'success' => false,
                'error' => $department,
            ], 422);
    }
    public function get_departments()
    {
        $departments = Department::with('colleges')->get();

        $departments->each(function ($department) {
            $department->comName = $department->colleges->name;
        });

        return response()->json($departments, 200);
    }
    public function get_department($id)
    {
        $department = Department::find($id);

        if (!$department) {
            return response()->json(['error' => 'department not found'], 404);
        }

        return response()->json($department, 200);
    }

    public function delete(Request $request)
    {
        $department = Department::find($request->id);

        if (!$department) {
            return response()->json(['error' => "Department not found $request->id "], 404);
        }
        $department->delete();

        return response()->json(['success' => true, 'message' => 'Department soft deleted successfully'], 200);
    }
    public function edit_department(Request $request)
    {
        $department = Department::find($request->id);

        if (!$department)
            return response()->json(['error' => 'Department not found'], 404);

        $department->name = $request->name;
        $department->college_id = $request->college;
        $department->chairperson = $request->chairperson;
        $department->description = $request->description;
        $department->save();

        return response()->json(['message' => 'Department updated successfully'], 200);
    }
}
