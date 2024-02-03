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
use Illuminate\Support\Facades\Crypt;


class RequestController extends Controller
{
    public function index()
    {
        return view('content.pages.requests.index');
    }
    public function request($id)
    {
        $request = Workflow::find($id);

        if ($request) {
            $authUser = auth()->user();
            $creator = $request->creator;

            $commonRoles = array_intersect($authUser->getRoleNames()->toArray(), $creator->getRoleNames()->toArray());
            $form_id = $request->form->id;
            if (!empty($commonRoles) || $authUser->hasRole('super-admin')) {
                return view('content.pages.requests.request', compact('form_id'));
            }
        }
        return view('404');
    }
    public function newRequests()
    {
        return view('content.pages.requests.new-requests');
    }

    public function getAll()
    {
        $user = auth()->user();
        if ($user->hasRole('super-admin')) {
            $workflows = Workflow::query()
                // ->with([
                //     'creator' => function ($query) {
                //         $query->select('id', 'first_name', 'last_name');
                //     }
                // ])
                ->with(['creator:id,first_name,last_name', 'form:id,name'])
                ->orderBy('created_at', 'desc')
                ->get();
            $workflows->each(function ($workflow) {
                $workflow->form->uid = Crypt::encryptString($workflow->form->id);
            });

        } else {
            if (!($user->can('forms_view')))
                return response()->json(403);

            $workflows = Workflow::where('created_by', $user->id)
                ->orWhereHas('creator', function ($query) use ($user) {
                    $query->role($user->roles);
                })
                ->with(['creator:id,first_name,last_name', 'form:id,name'])
                ->orderBy('created_at', 'desc')
                ->get();
            $workflows->each(function ($workflow) {
                $workflow->form->uid = Crypt::encryptString($workflow->form->id);
            });
        }
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
    public function filtered(Request $request)
    {
        $data = $request->selection;
        $entity = $data['entity'];
        $selection = $data['selection'];
        $workflows = Workflow::with(['creator:id,first_name,last_name', 'form:id,name'])->whereHas('creator.groups', function ($query) {
            $query->select('affiliations')->whereNotNull('affiliations');
        })->get();

        $filteredWorkflows = $workflows->filter(function ($workflow) use ($entity, $selection) {
            foreach ($workflow->creator->groups as $group) {
                $affiliations = json_decode($group->affiliations, true);

                if (isset($affiliations[$entity])) {
                    foreach ($affiliations[$entity] as $item) {
                        if (isset($item['name']) && $item['name'] === $selection) {
                            return true;
                        }
                    }
                }
            }

            return false;
        })->values()->toArray();


        return response()->json($filteredWorkflows, 200);
    }
}
