<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Center;
use App\Models\College;
use App\Models\Committe;
use App\Models\User;
use App\Models\Groups;

class GroupsController extends Controller
{
    public function createUserGroup()
    {
        // Display the HTML form for adding a user
        return view('content.pages.user.add-user-groups');
    }
    public function get_affiliations()
    {
        $data = new \stdClass();
        $data->users = User::select('id', 'first_name', 'last_name')->get();
        $data->affiliations = new \stdClass();

        $data->affiliations->committees = Committe::select('id', 'name')->get();
        $data->affiliations->centers = Center::select('id', 'name')->get();
        $data->affiliations->departments = Department::select('id', 'name')->get();
        $data->affiliations->colleges = College::select('id', 'name')->get();

        return response()->json($data, 200);
    }
    public function get_groups()
    {
        $groups = Groups::get();
        return response()->json($groups, 200);
    }

    public function addUsersGroup(Request $request)
    {
        // Validate and add the user to the database
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'permissions' => 'required|json',
            'users' => 'array',
            'affiliation' => 'required|json',
        ]);


        if ($validator->fails()) {
            // Validation failed
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new user record in the database
        $group = Groups::create([
            'name' => $request->get('name'),
            'affiliations' => $request->get('affiliation'),
            'permissions' => $request->get('permissions'),
        ]);

        if ($group && $request->has('users'))
            $group->users()->attach($request->input('users'));

        // Return a success response
        if ($group)
            return response()->json(['success' => true, 'message' => 'A user group added successfully'], 200);
        else
            return response()->json([
                'success' => false,
                'error' => $group,
            ], 422);
    }
    public function edit_groups_permissions(Request $request)
    {
        $group = Groups::find($request->id);

        if (!$group) {
            return response()->json(['error' => 'Group not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'permissions' => 'required|json',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        ;
        $group->permissions = $request->permissions;
        $group->save();

        return response()->json(['success' => true, 'message' => 'Groups permissions updated successfully'], 200);
    }

}
