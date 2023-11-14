<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Office;

class OfficeController extends Controller
{
    public function index()
    {
        return view('content.pages.offices.index');
    }
    public function create()
    {
        return view('content.pages.offices.create');
    }
    public function get()
    {
        $office = Office::all();

        return response()->json($office, 200);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'max:255',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new center record in the database
        $office = Office::create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
        ]);

        // Return a success response
        if ($office)
            return response()->json(['success' => true, 'message' => 'Office added successfully'], 200);
        else
            return response()->json([
                'success' => false,
                'error' => $office,
            ], 422);
    }


    public function office($id)
    {
        $office = Office::find($id);

        if (!$office) {
            return response()->json(['error' => 'office not found'], 404);
        }

        return response()->json($office, 200);
    }

    public function delete(Request $request)
    {
        $office = Office::find($request->id);

        if (!$office) {
            return response()->json(['error' => "Office not found $request->id "], 404);
        }
        $office->delete();

        return response()->json(['success' => true, 'message' => 'Office soft deleted successfully'], 200);
    }
    public function edit(Request $request)
    {
        $office = Office::find($request->id);

        if (!$office)
            return response()->json(['error' => 'office not found'], 404);

        $office->name = $request->name;
        $office->description = $request->description;
        $office->save();

        return response()->json(['message' => 'Office updated successfully'], 200);
    }


}
