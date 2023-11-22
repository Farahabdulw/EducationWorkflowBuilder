<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use App\Models\Forms;
use App\Models\User;
use App\Models\Step;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;


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
    public function edit()
    {
        return view('content.pages.forms.create');
    }
    public function create_category()
    {
        return view('content.pages.forms.category');
    }
    public function review_form($id, $step_id)
    {
        try {
            $step_id = Crypt::decryptString($step_id);
        } catch (DecryptException $e) {
            return view('404');
        }

        $step = Step::find($step_id);
        $form_id = $step->workflow->forms_id;
        if ($step)
            if ($step->user->id === auth()->user()->id)
                return view('content.pages.forms.review', compact('form_id'));
            else
                return view('403');

    }
    public function get_form_single($id)
    {
        $form = Forms::find($id);

        if (!$form) {
            return view('404');
        }

        $userRoles = auth()->user()->roles->pluck('name')->toArray();

        if (auth()->user()->hasRole($userRoles)) {
            return view('content.pages.forms.view', ['form' => $form]);
        } else {
            return view('403');
        }
    }
    public function review_form_progress(int $id, Request $request)
    {
        try {
            $step_id = Crypt::decryptString($request->step_key);
        } catch (DecryptException $e) {
            return response()->json(404);
        }

        $step = Step::find($step_id);
        if ($step) {
            $workflow = $step->workflow;
            // Retrieve all steps with status 1 or 2 for the same workflow
            $steps = Step::with(['workflow.form.creator', 'user'])
                ->where('workflow_id', $workflow->id)
                ->whereIn('status', [1, 2])
                ->get();
            $progress = $steps->map(function ($step) {
                return [
                    'id' => $step->id,
                    'created_at' => $step->created_at,
                    'workflow_id' => $step->workflow_id,
                    'user' => [
                        'id' => $step->user->id,
                        'first_name' => $step->user->first_name,
                        'last_name' => $step->user->last_name,
                    ],
                    'workflow' => [
                        'id' => $step->workflow->id,
                        'form' => [
                            'id' => $step->workflow->form->id,
                            'creator' => [
                                'id' => $step->workflow->form->creator->id,
                                'first_name' => $step->workflow->form->creator->first_name,
                                'last_name' => $step->workflow->form->creator->last_name,
                            ],
                        ],
                    ],
                ];
            });
            return response()->json($progress, 200);
        } else
            // If the step doesn't exist, return an appropriate response
            return response()->json(['error' => 'Step not found'], 404);
    }
    public function get_category()
    {
        $categories = Category::get();
        return response()->json($categories, 200);
    }
    public function add_category(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
        ]);


        if ($validator->fails()) {
            // Validation failed
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new category record in the database
        $category = Category::create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
        ]);

        // Return a success response
        if ($category)
            return response()->json(['success' => true, 'message' => 'Category added successfully', 'category' => $category], 200);
        else
            return response()->json([
                'success' => false,
                'error' => $category,
            ], 422);
    }
    public function add(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'categories' => 'required|array',
            'formData' => 'required|json',
        ]);

        // Insert form data into the forms table
        $form = Forms::create([
            'name' => $request->get('title'),
            'created_by' => auth()->user()->id,
            'content' => $request->get('formData'),
        ]);

        $categories = $request->get('categories');
        $form->categories()->sync($categories);
        // Return a success response
        return response()->json(['success' => true, 'message' => 'Form added successfully', 'id' => $form->id], 200);
    }
    public function get_forms(Request $request)
    {
        $forms = Forms::with('categories')->get();
        return response()->json($forms, 200);
    }
    public function get_form(Request $request, $id)
    {
        $form = Forms::with('categories', 'creator')->find($id);
        if ($form)
            return response()->json($form, 200);
        else
            return response()->json(['error' => 'form not found'], 404);
    }
    public function get_content($id)
    {
        $formContent = Forms::select('content')->find($id);
        return response()->json($formContent, 200);
    }
    public function update(Request $request)
    {
        $form = Forms::find($request->id);
        if ($form) {
            $request->validate([
                'title' => 'required|string',
                'categories' => 'required|array',
                'formData' => 'required|json',
            ]);
            // Insert form data into the forms table
            $form->name = $request->get('title');
            $form->content = $request->get('formData');
            $form->save();
            $categories = $request->get('categories');
            $form->categories()->sync($categories);

            // Return a success response
            return response()->json(['success' => true, 'message' => 'Form updated successfully'], 200);
        } else
            return response()->json(['success' => FALSE, 'message' => 'Form not found'], 404);

    }
    public function get_forms_users()
    {
        $user = auth()->user();
        if ($user->hasRole('super-admin')) {
            $users = User::get();
            return response()->json($users, 200);
        }
        $roles = $user->roles;

        $uniqueUserIds = [];
        foreach ($roles as $role) {
            $usersInRole = $role->users;
            foreach ($usersInRole as $userInRole) {
                if (!in_array($userInRole->id, $uniqueUserIds)) {
                    $uniqueUserIds[] = $userInRole->id;
                }
            }
        }

        $uniqueUsers = User::whereIn('id', $uniqueUserIds)->get();

        return response()->json($uniqueUsers, 200);

    }
}
