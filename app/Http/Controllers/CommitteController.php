<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Committe;
use DB;

class CommitteController extends Controller
{
    public function index()
    {
        return view('content.pages.committees.committees');
    }

    public function add()
    {
        return view('content.pages.committees.committees-add');
    }

    public function addCom(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'cname' => 'required',
            'chairperson' => 'required',
            'description' => 'max:255',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new committe record in the database
        $committe = Committe::create([
            'name' => $request->get('cname'),
            'chairperson' => $request->get('chairperson'),
            'description' => $request->get('description'),
        ]);

        // Return a success response
        if ($committe)
            return response()->json(['success' => true, 'message' => 'Committe added successfully'], 200);
        else
            return response()->json([
                'success' => false,
                'error' => $committe,
            ], 422);
    }
    public function get_committees()
    {
        $committees = DB::table('committes')
            ->join('users', 'committes.chairperson', '=', 'users.id')
            ->select('committes.*', 'users.first_name', 'users.last_name')
            ->whereNull('committes.deleted_at')
            ->get();
            

        foreach ($committees as $committee) {
            $committee->chairpersonName = $committee->first_name . " " . $committee->last_name;
        }

        return response()->json($committees, 200);
    }
    public function get_committee($id)
    {
        $committee = Committe::find($id);

        if (!$committee) {
            return response()->json(['error' => 'Committee not found'], 404);
        }

        $formattedUser = [
            'id' => $committee->id,
            'name' => $committee->name,
            'chairperson' => $committee->chairperson,
            'description' => $committee->description,
        ];

        return response()->json($formattedUser, 200);
    }

    public function delete(Request $request)
    {
        $committee = Committe::find($request->id);

        if (!$committee) {
            return response()->json(['error' => "Committee not found $request->id "], 404);
        }
        $committee->delete();

        return response()->json(['success' => true, 'message' => 'Committee soft deleted successfully'], 200);
    }
    public function edit_committee(Request $request)
    {
        $committee = Committe::find($request->id);

        if (!$committee)
            return response()->json(['error' => 'committee not found'], 404);

        $committee->name = $request->name;
        $committee->chairperson = $request->chairperson;
        $committee->description = $request->description;
        $committee->save();

        return response()->json(['message' => 'committee updated successfully'], 200);
    }

}
