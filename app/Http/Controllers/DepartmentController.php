<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        return view('content.pages.departments.index');
    }
    public function create()
    {
        return view('content.pages.departments.create');
    }
}
