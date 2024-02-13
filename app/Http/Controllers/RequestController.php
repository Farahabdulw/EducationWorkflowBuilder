<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Department;
use App\Models\Center;
use App\Models\College;
use App\Models\Office;
use App\Models\Forms;
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
    public function get_requests_forms(Request $request)
    {
        # get all groups and json_decode the affilations column 
        # itterate each group and look for the groups that have center.id and college as the given center and college
        # get all the users from these groups 
        # get all the forms they submitited with there workflows
        # filter out the forms to be by category id like the given 

        $user = auth()->user();
        $center = $request->center;
        $college = $request->college;
        $category = $request->type;
        // Retrieve user's groups with affiliations
        $groups = $user->groups;

        // Filter forms based on center and college from user's groups
        $forms = collect();

        foreach ($groups as $group) {
            $affiliations = json_decode($group->affiliations, true);

            if (
                isset($affiliations['centers']) && collect($affiliations['centers'])->pluck('id')->contains($center) &&
                isset($affiliations['colleges']) && collect($affiliations['colleges'])->pluck('id')->contains($college)
            ) {
                $forms = $forms->merge($group->users->flatMap(function ($user) {
                    return $user->forms;
                }));
            }
        }

        // Filter forms further by category
        $forms = $forms->filter(function ($form) use ($category) {
            return $form->categories->contains('id', $category);
        });

        // Retrieve workflows for the filtered forms
        $forms = Forms::whereIn('id', $forms->pluck('id'))
            ->with([
                'workflows' => function ($query) {
                    $query->select('workflows.id', 'workflows.created_at', 'workflows.forms_id')
                        ->orderByDesc('created_at')
                        ->whereNotNull('forms_id');
                }
            ])
            ->whereHas('workflows', function ($query) {
                $query->whereNotNull('workflows.id');
            })
            ->orderBy('created_at', 'desc')
            ->get(['id', 'name']);

        foreach ($forms as $form) {
            $encryptedFormId = Crypt::encryptString($form->id);
            $form->uid = $encryptedFormId;
            $form->makeHidden(['id']);

            if (isset($form->workflows[0])) {
                $workflow = $form->workflows[0];
                $encryptedWorkflowId = Crypt::encryptString($workflow->id);
                $workflow->uid = $encryptedWorkflowId;
                $workflow->makeHidden(['id']);
                $form->workflows = [$workflow];
            } else {
                $form->workflows = [];
            }
        }

        $formsArray = $forms->toArray();

        return response()->json(['forms' => $formsArray], 200);
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
