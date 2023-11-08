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
        return view('content.pages.users');
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
            'permissions' => 'json',
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
            'permissions' => $request->get('permissions'),
        ]);

        // Return a success response
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
                'permissions' => $user->permissions,
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
    public function edit_user_permissions(Request $request)
    {
        $user = User::find($request->id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'permissions' => 'required|json',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        };
        $user->permissions = $request->permissions;
        $user->save();

        return response()->json(['success' => true, 'message' => 'User permissions updated successfully'], 200);
    }


}

