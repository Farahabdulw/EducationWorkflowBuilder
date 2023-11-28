<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Department;
use App\Models\Center;
use App\Models\College;
use App\Models\Office;
use App\Models\Committe;
use App\Models\Workflow;

class RequestController extends Controller
{
    public function index()
    {
        return view('content.pages.requests.index');
    }
    public function getAll()
    {
        $workflows = Workflow::query()
            ->with([
                'creator' => function ($query) {
                    $query->select('id', 'first_name', 'last_name');
                }
            ])->get();
        return response()->json($workflows, 200);
    }
    public function filters()
    {
        $filters = [];
        $filters['committees'] = Committe::select('id', 'name')->get();
        $filters['offices'] = Office::select('id', 'name')->get();
        $filters['Colleges'] = College::select('id', 'name')->get();
        $filters['Departments'] = Department::select('id', 'name')->get();
        $filters['Centers'] = Center::select('id', 'name')->get();
        return response()->json($filters, 200);

    }
    public function filtered(Request $request){

    }
}
