<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormsController extends Controller
{
    public function index()
    {
        return view('content.pages.forms.index');
    }
    public function create()
    {
        return view('content.pages.forms.create');
    }
}
