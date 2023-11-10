<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CenterController extends Controller
{
    public function index()
    {
        return view('content.pages.centers.index');
    }
    public function create()
    {
        return view('content.pages.centers.create');
    }
}
