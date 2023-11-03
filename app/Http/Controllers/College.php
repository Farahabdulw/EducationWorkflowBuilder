<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class College extends Controller
{
    public function index()
    {
        return view('content.pages.colleges');
    }
}
