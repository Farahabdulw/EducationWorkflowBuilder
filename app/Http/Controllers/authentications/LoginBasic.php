<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginBasic extends Controller
{
  public function index()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.auth-login-basic', ['pageConfigs' => $pageConfigs]);
  }
  
  public function auth(Request $request)
  {
      // Validate the user's input
      $request->validate([
          'email' => 'required',
          'password' => 'required',
      ]);
      
      $credentials = $request->only('email', 'password');
      
      // Attempt to authenticate the user
      if (Auth::attempt($credentials)) {
          // Authentication successful
          // Redirect to a protected resource or return a response
          return redirect()->route('dashboard'); // Adjust the route name as needed
      } else {
          // Authentication failed
          // Redirect back to the login form with an error message
          return redirect()->route('login.basic')->with('error', 'Invalid credentials');
      }
  }
}
