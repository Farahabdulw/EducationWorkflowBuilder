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
use PhpOffice\PhpWord\IOFactory;
use App\Models\Groups;

use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

use App\Models\FrequentUsed;


class FormsController extends Controller
{
    public function index()
    {
        return view('content.pages.forms.index');
    }
    public function create(Request $request)
    {
        if ($request->has('previous')) {
            $form_id = $request->input('previous');
            try {
                $id = Crypt::decryptString($form_id);
            } catch (DecryptException $e) {
                return view('404');
            }
            $form = Forms::with('categories')->find($id);
            if ($form) {
                $dataArray = json_decode($form->content, true);
                foreach ($dataArray as &$item) {
                    if (isset($item['value'])) {
                        $item['value'] = '';
                    }
                }
                $form->content = json_encode($dataArray);
                return view('content.pages.forms.create', compact('form'));
            }


        }
        return view('content.pages.forms.create');
    }
    public function clone_form(Request $request)
    {
        $request->validate([
            'preF_id' => 'required',
        ]);
        $this->saveContentasFrequent($request->get('formData'));
        // Find the form to clone
        $formToClone = Forms::findOrFail($request->get('preF_id'));

        $form = Forms::create([
            'name' => $formToClone->name,
            'created_by' => auth()->user()->id,
            'file' => $formToClone->file,
            'content' => $request->get('formData')
        ]);

        $categories = $formToClone->categories()->pluck('category_id')->toArray();
        $form->categories()->sync($categories);

        $form->save();

        return response()->json(['success' => true, 'message' => 'Form saved successfully', 'id' => $form->id], 200);
    }
    public function edit($id)
    {
        $form = Forms::find($id);
        if (!$form) {
            return view('404');
        }
        return view('content.pages.forms.edit');
    }
    public function create_category()
    {
        return view('content.pages.forms.category');
    }
    public function form_summation($id)
    {
        $id = Crypt::decryptString($id);
        $form = Forms::find($id);
        $userRoles = $form->creator->roles->pluck('name')->toArray();
        $groups = auth()->user()->roles->pluck('name')->toArray();
        $affiliations = Groups::whereIn('name', $userRoles)->pluck('affiliations')->toArray();
        $centers = [];
        foreach ($affiliations as $affiliation) {
            $aff = json_decode($affiliation, true);

            if (isset($aff['centers']))
                $centers = array_merge($centers, $aff['centers']);
        }
        if (!$form || !auth()->user()->hasRole($userRoles) && !auth()->hasRole('super-admin'))
            return view('404');
        else
            return view('content.pages.forms.submit', compact('form', 'centers'));

    }
    public function form_file($id)
    {
        $form = Forms::find($id);

        if (!$form)
            return view('404');
        if ($form->file && $form->file != '')
            return response()->file(storage_path('app/private/' . $form->file));

        $filePath = storage_path('app/private/forms/form-' . $form->id . ".pdf");
        if (file_exists($filePath)) {
            return response()->file($filePath, ['Content-Type' => 'application/pdf'], 200);
        } else {
            return view('content.pages.forms.pdf', ['form' => $form, 'formId' => $form->id, 'redirectToPdf' => true]);
        }
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
            if ($step->user->id === auth()->user()->id) {
                $status = 0; // Default status
                switch ($step->status) {
                    case 0:
                    case 2:
                    case 3:
                    case 5:
                        $status = 1;
                        break;
                    case 1:
                        $status = 0;
                        break;
                    case 4:
                        $nextStep = $step->workflow->steps()->where('step', '>', $step->step)->orderBy('step')->first();

                        if ($nextStep && in_array($nextStep->status, [0, 1, 3])) {
                            $status = 1;
                        }
                        break;
                }
                $forwarded = $step->forwarded_from ? true : false;
                $firstStep = $step->workflow->steps()
                    ->orderBy('step')
                    ->first();
                $isFirstStep = ($step->id === $firstStep->id);


                $returnReason = null;

                $prevSteps = Step::where('workflow_id', $step->workflow_id)
                    ->where('step', $step->step - 1)
                    ->where('status', 5)
                    ->get();

                if ($prevSteps->count() > 0) {
                    // Get the return reason from the latest returned step for the same user
                    $returnReason = $prevSteps->last()->review;
                }


                return view('content.pages.forms.review', compact('form_id', 'status', 'forwarded', 'isFirstStep', 'returnReason'));

            } else
                return view('403');

    }
    public function get_form_single($id, $redirectToPdf = false)
    {
        $form = Forms::find($id);

        if (!$form) {
            return view('404');
        }

        $userRoles = $form->creator->roles->pluck('name')->toArray();

        if (auth()->user()->hasRole($userRoles)) {
            return view('content.pages.forms.view', ['form' => $form, 'formId' => $form->id]);
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
        if ($step)
            if ($step->user->id === auth()->user()->id) {
                $workflow = $step->workflow;
                $steps = Step::with(['workflow.form.creator', 'user'])
                    ->where('workflow_id', $workflow->id)
                    ->whereIn('status', [1, 2])
                    ->orderBy('step')
                    ->get();
                $progress = $steps->map(function ($step) {
                    return [
                        'id' => $step->id,
                        'status' => $step->status,
                        'review' => $step->review,
                        'forwarded_from' => $step->forwarded_from,
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
                return view('403');
        else
            // If the step doesn't exist, return an appropriate response
            return response()->json(['error' => 'Step not found'], 404);
    }
    public function get_category()
    {
        $categories = Category::get();
        return response()->json($categories, 200);
    }
    public function edit_category($id, Request $request)
    {
        $category = Category::find($id);
        if ($category) {
            $category->name = $request->name;
            $category->save();
            return response()->json($category, 200);
        }
        return response()->json(['error' => 'category not found'], 404);

    }
    public function delete_category($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->delete();
            return response()->json(['message' => 'Category deleted successfully'], 200);
        }
        return response()->json(['error' => 'category not found'], 404);

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
            'categories' => 'required',
            'formData' => 'required|json',
        ]);

        $form = Forms::create([
            'name' => $request->get('title'),
            'created_by' => auth()->user()->id,
            'content' => $request->get('formData'),
        ]);
        $this->saveContentasFrequent($request->get('formData'));
        if ($request->hasFile('formFile')) {
            $file = $request->file('formFile');
            $fileName = "form1." . $file->extension();
            $file->move(public_path('uploads'), $fileName);

            $credentialsPath = storage_path('app/private/credentials.json');
            $client = new Google_Client();
            $client->setAuthConfig($credentialsPath);
            $client->addScope(Google_Service_Drive::DRIVE);
            $client->addScope(Google_Service_Drive::DRIVE_FILE);
            $driveService = new Google_Service_Drive($client);

            $target_file = public_path('uploads/' . $fileName);
            $fileMetadata = new Google_Service_Drive_DriveFile(
                array(
                    'name' => 'toBeConverted.docx',
                    'mimeType' => 'application/vnd.google-apps.document'
                )
            );
            $content = file_get_contents($target_file);

            $file = $driveService->files->create(
                $fileMetadata,
                array(
                    'data' => $content,
                    'fields' => 'id'
                )
            );

            $file_name = str_replace('.docx', '.pdf', 'form.docx');
            $pdfFileName = $form->id . "-form" . '.pdf';

            $filePath = storage_path('app/private/' . $pdfFileName);
            $attempt = 1;
            do {
                usleep(500000);
                $content = $driveService->files->export($file->id, 'application/pdf', array('alt' => 'media'));

                file_put_contents($filePath, $content->getBody()->getContents());

                if (filesize($filePath))
                    break;
                else
                    $attempt++;
                if ($attempt > 5)
                    die('converstion didnt work');
            } while (true);
            $driveService->files->delete($file->id);

            $form->file = $pdfFileName;
        } elseif ($request->uid && $request->uid != null) {
            try {
                $uid = Crypt::decryptString($request->uid);
            } catch (DecryptException $e) {
                return view('404');
            }
            $Preform = Forms::find($uid);
            $form->file = $Preform->file;
        }
        $form->save();

        $categories = json_decode($request->get('categories'));
        $form->categories()->sync($categories);
        return response()->json(['success' => true, 'message' => 'Form added successfully', 'id' => $form->id], 200);
    }
    public function saveContentasFrequent($content)
    {
        $dataArray = json_decode($content, true);
        $labels = [];

        foreach ($dataArray as &$item)
            if (isset($item['label']))
                $labels[] = $item['label']; foreach ($labels as $text) {
            $existingRecord = FrequentUsed::where('text', $text)->first();
            if (!$existingRecord)
                FrequentUsed::create(['text' => $text]);
        }

    }

    public function get_forms(Request $request)
    {
        $user = auth()->user();
        if ($user->hasRole('super-admin')) {
            $canEdit = true;
            $canDelete = true;
            $canAdd = true;
            $forms = Forms::with('categories')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $authUser = $user;
            $canEdit = $user->can('forms_edit');
            $canDelete = $user->can('forms_delete');
            $canAdd = $user->can('forms_add');

            $authUserRoles = $authUser->getRoleNames();
            $users = User::role($authUserRoles)->get();
            $formIds = $users->flatMap(function ($user) {
                return $user->forms->pluck('id');
            })->unique()->toArray();

            $forms = Forms::whereIn('id', $formIds)
                ->orderBy('created_at', 'desc')
                ->with('categories')
                ->get();
        }
        $responseObject = [
            'forms' => $forms,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete,
            'canAdd' => $canAdd,
        ];
        return response()->json($responseObject, 200);

    }
    public function get_categorys_forms(Request $request)
    {
        $category = $request->id;
        $user = auth()->user();
        if ($user->hasRole('super-admin')) {
            $forms = Forms::with('categories')
                ->whereHas('categories', function ($query) use ($category) {
                    $query->where('categories.id', $category);
                })->orderBy('created_at', 'desc')
                ->get();
        } else {
            $authUser = $user;

            $authUserRoles = $authUser->getRoleNames();
            $users = User::role($authUserRoles)->get();
            $formIds = $users->flatMap(function ($user) {
                return $user->forms->pluck('id');
            })->unique()->toArray();

            $forms = Forms::whereIn('id', $formIds)
                ->orderBy('created_at', 'desc')
                ->with('categories')
                ->whereHas('categories', function ($query) use ($category) {
                    $query->where('id', $category);
                })
                ->get();
        }

        return response()->json($forms, 200);
    }
    public function get_forms_new_requests(Request $request)
    {
        $user = auth()->user();

        $forms = Forms::with([
            'categories' => function ($query) {
                $query->select('categories.id', 'categories.name');
            },
            'workflows' => function ($query) {
                $query->select('workflows.id', 'workflows.created_at', 'forms_id')
                    ->orderByDesc('created_at')
                    ->take(1);
            }
        ])
            ->orderBy('created_at', 'desc')
            ->get(['id', 'name']);

        foreach ($forms as $form) {
            $encryptedFormId = Crypt::encryptString($form->id);
            $form->uid = $encryptedFormId;
            $form->makeHidden(['id']);

            foreach ($form->workflows as $workflow) {
                $encryptedWorkflowId = Crypt::encryptString($workflow->id);
                $workflow->uid = $encryptedWorkflowId;
                $workflow->makeHidden(['id']);
            }
        }

        return response()->json(['forms' => $forms], 200);
    }


    public function get_form(Request $request, $id)
    {
        if (strlen($id) === 200) {
            try {
                $id = Crypt::decryptString($id);
            } catch (DecryptException $e) {
                return response()->json(['error' => 'Invalid encrypted ID'], 400);
            }
        }
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
                'categories' => 'required',
                'formData' => 'required|json',
            ]);
            // Insert form data into the forms table
            $form->name = $request->get('title');
            $form->content = $request->get('formData');
            $this->saveContentasFrequent($form->content);
            if ($request->hasFile('formFile')) {
                $file = $request->file('formFile');
                $fileName = "form1." . $file->extension();
                $file->move(public_path('uploads'), $fileName);

                $credentialsPath = storage_path('app/private/credentials.json');
                $client = new Google_Client();
                $client->setAuthConfig($credentialsPath);
                $client->addScope(Google_Service_Drive::DRIVE);
                $client->addScope(Google_Service_Drive::DRIVE_FILE);
                $driveService = new Google_Service_Drive($client);

                $target_file = public_path('uploads/' . $fileName);
                $fileMetadata = new Google_Service_Drive_DriveFile(
                    array(
                        'name' => 'toBeConverted.docx',
                        'mimeType' => 'application/vnd.google-apps.document'
                    )
                );
                $content = file_get_contents($target_file);

                $file = $driveService->files->create(
                    $fileMetadata,
                    array(
                        'data' => $content,
                        'fields' => 'id'
                    )
                );

                $file_name = str_replace('.docx', '.pdf', 'form.docx');
                $pdfFileName = $form->id . "-form" . '.pdf';

                $filePath = storage_path('app/private/' . $pdfFileName);
                $attempt = 1;
                do {
                    usleep(500000);
                    $content = $driveService->files->export($file->id, 'application/pdf', array('alt' => 'media'));

                    file_put_contents($filePath, $content->getBody()->getContents());

                    if (filesize($filePath))
                        break;
                    else
                        $attempt++;
                    if ($attempt > 5)
                        die('converstion didnt work');
                } while (true);
                $driveService->files->delete($file->id);

                $form->file = $pdfFileName;
            }
            $filePath = storage_path('app/private/forms/form-' . $form->id . ".pdf");
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $form->save();
            $categories = json_decode($request->get('categories'));
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

    public function download_form_file($id)
    {
        $id = Crypt::decryptString($id);
        $form = Forms::find($id);
        if (!$form) {
            return view('404');
        }

        $filePath = storage_path('app/private/' . $form->file);

        if (file_exists($filePath))
            return response()->download($filePath, $form->file);
        else
            return view('404');
    }
}
