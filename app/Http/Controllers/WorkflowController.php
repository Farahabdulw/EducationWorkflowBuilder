<?php

namespace App\Http\Controllers;

use App\Notifications\FormReceived;
use Illuminate\Http\Request;
use App\Models\User;
class WorkflowController extends Controller
{
    public function create(Request $request)
    {
        $users = $request->users_chain;
        $user = User::find($users[0]["id"]);
        $user->notify(new FormReceived('http://18.204.18.221/form/edit/1'));
    }
}
