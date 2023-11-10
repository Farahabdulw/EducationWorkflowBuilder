<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\College;

class CollegeController extends Controller
{
    public function index()
    {
        return view('content.pages.colleges.index');
    }
    public function create()
    {
        return view('content.pages.colleges.create');
    }
    public function add_college(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'committee' => 'required',
            'description' => 'max:255',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new college record in the database
        $college = College::create([
            'name' => $request->get('name'),
            'committee_id' => $request->get('committee'),
            'description' => $request->get('description'),
        ]);

        // Return a success response
        if ($college)
            return response()->json(['success' => true, 'message' => 'College added successfully'], 200);
        else
            return response()->json([
                'success' => false,
                'error' => $college,
            ], 422);
    }
    public function get_colleges()
    {
        $colleges = College::with('committee')->get();

        $colleges->each(function ($college) {
            $college->comName =  $college->committee->name;
        });

        return response()->json($colleges, 200);
    }
    public function get_college($id)
    {
        $college = College::find($id);

        if (!$college) {
            return response()->json(['error' => 'college not found'], 404);
        }

        return response()->json($college, 200);
    }

    public function delete(Request $request)
    {
        $college = College::find($request->id);

        if (!$college) {
            return response()->json(['error' => "College not found $request->id "], 404);
        }
        $college->delete();

        return response()->json(['success' => true, 'message' => 'College soft deleted successfully'], 200);
    }
    public function edit_college(Request $request)
    {
        $college = College::find($request->id);

        if (!$college)
            return response()->json(['error' => 'college not found'], 404);

        $college->name = $request->name;
        $college->committee_id = $request->committee_id;
        $college->description = $request->description;
        $college->save();

        return response()->json(['message' => 'College updated successfully'], 200);
    }
}
