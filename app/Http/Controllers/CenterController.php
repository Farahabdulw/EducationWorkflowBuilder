<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Center;
class CenterController extends Controller
{
    public function index()
    {
        return view('content.pages.centers.index');
    }
    public function create()
    {
        return view('content.pages.centers.create');
    }

    public function add_center(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'department' => 'required',
            'description' => 'max:255',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new center record in the database
        $center = Center::create([
            'name' => $request->get('name'),
            'department_id' => $request->get('department'),
            'description' => $request->get('description'),
        ]);

        // Return a success response
        if ($center)
            return response()->json(['success' => true, 'message' => 'Center added successfully'], 200);
        else
            return response()->json([
                'success' => false,
                'error' => $center,
            ], 422);
    }
    public function get_centers()
    {
        $centers = Center::with('departments')->get();

        return response()->json($centers, 200);
    }
    public function get_center($id)
    {
        $center = Center::with('departments')->find($id);

        if (!$center) {
            return response()->json(['error' => 'center not found'], 404);
        }
        return response()->json($center, 200);
    }

    public function delete(Request $request)
    {
        $center = Center::find($request->id);

        if (!$center) {
            return response()->json(['error' => "Center not found $request->id "], 404);
        }
        $center->delete();

        return response()->json(['success' => true, 'message' => 'Center soft deleted successfully'], 200);
    }    
    public function edit_center(Request $request)
    {
        $center = Center::find($request->id);

        if (!$center)
            return response()->json(['error' => 'center not found'], 404);

        $center->name = $request->name;
        $center->department_id = $request->department_id;
        $center->description = $request->description;
        $center->save();

        return response()->json(['message' => 'Center updated successfully'], 200);
    }
}
