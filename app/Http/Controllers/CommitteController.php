<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Committe;

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

}
