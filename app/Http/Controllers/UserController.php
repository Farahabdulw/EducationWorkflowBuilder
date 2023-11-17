<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Models\User;


class UserController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $usersGroups = $user->groups;
        $permissions = [];
        foreach ($usersGroups as $group) {
            $groupPermissions = json_decode($group->permissions, true);

            foreach ($groupPermissions as $resource => $actions) {
                if (!isset($permissions[$resource])) {
                    $permissions[$resource] = [];
                }

                // Merge actions without duplicates
                $permissions[$resource] = array_unique(array_merge($permissions[$resource], $actions));
            }
        }

        return view('content.pages.users', compact('permissions', 'usersGroups'));
    }

    public function addForm()
    {
        // Display the HTML form for adding a user
        return view('content.pages.user-add');
    }
    public function createUser(Request $request)
    {
        // Validate and add the user to the database
        $validator = Validator::make($request->all(), [
            'fname' => 'required',
            'lname' => 'required',
            'birthdate' => 'required|date',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'groups' => 'array',
        ]);


        if ($validator->fails()) {
            // Validation failed
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new user record in the database
        $user = User::create([
            'first_name' => $request->get('fname'),
            'last_name' => $request->get('lname'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'birthdate' => $request->get('birthdate'),
        ]);
        // add the newly created user to the each group 

        if (isset($request->groups)) {
            $user->groups()->sync($request->groups);
        }
        if ($user)
            return response()->json(['success' => true, 'message' => 'User added successfully'], 200);
        else
            return response()->json([
                'success' => false,
                'error' => $user,
            ], 422);
    }
    public function get_users()
    {
        $users = User::all();

        $formattedUsers = [];
        foreach ($users as $user) {
            $birthdate = Carbon::parse($user->birthdate);
            $age = $birthdate->age;
            $formattedUser = [
                'id' => $user->id,
                'fname' => $user->first_name,
                'email' => $user->email,
                'lname' => $user->last_name,
                'age' => $age,
            ];
            $formattedUsers[] = $formattedUser;
        }
        return response()->json($formattedUsers, 200);
    }
    public function get_user($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $formattedUser = [
            'id' => $user->id,
            'fname' => $user->first_name,
            'email' => $user->email,
            'lname' => $user->last_name,
            'birthdate' => $user->birthdate,
        ];

        return response()->json($formattedUser, 200);
    }

    public function edit_user(Request $request)
    {
        $user = User::find($request->id);

        if (!$user)
            return response()->json(['error' => 'User not found'], 404);

        $user->first_name = $request->fname;
        $user->last_name = $request->lname;
        $user->email = $request->email;
        $user->birthdate = $request->birthdate;
        $user->save();

        return response()->json(['message' => 'User updated successfully'], 200);
    }

    public function delete(Request $request)
    {
        $user = User::find($request->id);

        if (!$user) {
            return response()->json(['error' => "User not found $request->id "], 404);
        }
        $user->delete();

        return response()->json(['success' => true, 'message' => 'User soft deleted successfully'], 200);
    }
    public function get_current_user()
    {
        return response()->json(auth()->user()->id, 200);

    }
}

