<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Committe extends Controller
{
    public function index()
    {
        return view('content.pages.committees');
    }
}
