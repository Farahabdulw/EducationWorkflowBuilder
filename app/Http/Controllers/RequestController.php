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
use App\Models\Groups;
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

        $user = auth()->user();
        $center = $request->center;
        $college = $request->college;
        $category = $request->type;
        $groups = $user->groups;

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
        $groups = auth()->user()->roles->pluck('name')->toArray();
        $affiliations = Groups::whereIn('name', $groups)->pluck('affiliations')->toArray();

        $decodedAffiliations = array_map('json_decode', $affiliations);

        $filters = [
            'committees' => [],
            'offices' => [],
            'Colleges' => [],
            'Departments' => [],
            'Centers' => [],
        ];

        foreach ($decodedAffiliations as $affiliation) {
            $filters['committees'] = array_merge($filters['committees'], $affiliation->committees ?? []);
            $filters['offices'] = array_merge($filters['offices'], $affiliation->offices ?? []);
            $filters['Colleges'] = array_merge($filters['Colleges'], $affiliation->colleges ?? []);
            $filters['Departments'] = array_merge($filters['Departments'], $affiliation->departments ?? []);
            $filters['Centers'] = array_merge($filters['Centers'], $affiliation->centers ?? []);
        }

        $filters['committees'] = array_values(array_unique($filters['committees'], SORT_REGULAR));
        $filters['offices'] = array_values(array_unique($filters['offices'], SORT_REGULAR));
        $filters['Colleges'] = array_values(array_unique($filters['Colleges'], SORT_REGULAR));
        $filters['Departments'] = array_values(array_unique($filters['Departments'], SORT_REGULAR));
        $filters['Centers'] = array_values(array_unique($filters['Centers'], SORT_REGULAR));

        return response()->json($filters, 200);
    }
    public function filtered(Request $request)
    {
        $data = $request->selection;
        $entity = $data['entity'];
        $selection = $data['selection'];

        // Retrieve the user's groups and affiliations
        $user = auth()->user();
        $groups = $user->groups;

        $filteredWorkflows = [];

        // Loop through each group and check affiliations
        foreach ($groups as $group) {
            $affiliations = json_decode($group->affiliations, true);

            if (isset($affiliations[$entity])) {
                foreach ($affiliations[$entity] as $item) {
                    if (isset($item['name']) && $item['name'] === $selection) {
                        // If the affiliations match, retrieve workflows for the creator
                        $workflows = Workflow::whereHas('creator', function ($query) use ($group) {
                            $query->where('id', $group->user_id);
                        })->with(['creator:id,first_name,last_name', 'form:id,name'])->get();

                        // Merge the workflows into the result array
                        $filteredWorkflows = array_merge($filteredWorkflows, $workflows->toArray());
                    }
                }
            }
        }

        return response()->json($filteredWorkflows, 200);
    }

}
