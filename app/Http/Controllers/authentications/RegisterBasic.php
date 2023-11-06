<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterBasic extends Controller
{
  public function index()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.auth-register-basic', ['pageConfigs' => $pageConfigs]);
  }
  public function register(Request $request)
  {
      // Validate the registration form fields
      $validation = $request->validate([
          'fname' => 'required',
          'lname' => 'required',
          'email' => 'required|email|unique:users',
          'password1' => 'required|min:8',
          'password2' => 'required|same:password1',
          'permissions' => 'required',
      ]);
      

      $permissions = json_decode($request->input('permissions'), true);
      
      if ($permissions === null && json_last_error() !== JSON_ERROR_NONE) {
          return response()->json(['error' => 'Invalid JSON format for permissions'], 422);
      }
      
      // If validation fails, return a JSON response with errors
      if ($validation->fails()) {
          return response()->json(['errors' => $validation->errors()], 422);
      }
      
      $user = User::create([
          'first_name' => $request->input('fname'),
          'last_name' => $request->input('lname'),
          'email' => $request->input('email'),
          'password' => Hash::make($request->input('password1')),
          'permissions' => json_encode($permissions),
      ]);
      
      Auth::login($user);
      
      return redirect()->route('/');
  }
  
  
}
