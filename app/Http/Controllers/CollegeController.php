<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CollegeController extends Controller
{
    public function index()
    {
        return view('content.pages.colleges.index');
    }
    public function create(){
        return view('content.pages.colleges.create');
    }
}
