<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

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
      return response()->json([
        'success' => true,
        'route' => 'users',
      ], 200);
    } else {
      // Authentication failed
      return response()->json([
        'success' => false,
        'message' => 'Email or Password not found',
      ], 422);
    }
  }
  public function logout()
  {
    Auth::logout();
    return redirect('/');
  }
}
