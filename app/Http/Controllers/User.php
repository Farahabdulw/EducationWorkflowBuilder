<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class User extends Controller
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
    
    public function addUser(Request $request)
    {
        // Validate and add the user to the database
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
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
        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'permissions' => $request->input('permissions'),
        ]);
        
        // Return a success response
        return response()->json(['success' => true, 'message' => 'User added successfully'], 200);
    }
}

