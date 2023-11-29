<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Notifications\FormReceived;
use App\Notifications\FormCompletion;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Workflow;
use App\Models\Step;
use App\Models\Forms;
use Illuminate\Support\Facades\DB;

class WorkflowController extends Controller
{
    public function create(Request $request)
    {
        $users = $request->users;
        $form_id = $request->form;
        $sender_id = $request->sender_id;
        $affiliations = $request->affiliations;
        $workflow = Workflow::create([
            "forms_id" => $form_id,
            "status" => 0, // 0 => pending , 1 => in progress , 2 => completed/over
            "created_by" => $sender_id,
            "affiliations" => $affiliations,
        ]);

        foreach ($users as $index => $user) {
            // Create a step for each user in the workflow
            $step = Step::create([
                'workflow_id' => $workflow->id,
                'user_id' => $user['id'],
                'step' => $index + 1,
                'status' => 0, // 0 => pending , 1 => inProgress , 2 => approved , 3 => rejected , 4 => forwarded
            ]);
        }
        $this->lunchWorkflow($workflow, $sender_id, $form_id);
        return response()->json(["successful" => true, "form" => $form_id], 200);

    }
    public function lunchWorkflow(Workflow $workflow, int $sender_id, $form_id)
    {
        $workflow->status = 1;
        $workflow->save();
        $firstStep = $workflow->steps()->orderBy('step')->first();
        Step::find($firstStep->id)->update([
            'status' => 1
        ]);
        $sender = User::find($sender_id);
        $recipient = User::find($firstStep->user_id);

        $message = "You received a form from {$sender->first_name} {$sender->last_name} that needs your action";

        // Notify the recipient
        $recipient->notify(new FormReceived($sender_id, $message, $form_id, $firstStep->id));
    }
    public function get($id, Request $request)
    {

        $form = Forms::find($id);

        if (!$form) {
            return view('404');
        }

        $userRoles = auth()->user()->roles->pluck('name')->toArray();

        if (auth()->user()->hasRole($userRoles) || auth()->user()->hasRole('super-admin')) {
            $workflows = Workflow::query()
                ->with([
                    'creator' => function ($query) {
                        $query->select('id', 'first_name', 'last_name');
                    }
                ])->where('forms_id', $form->id)->get();
            return response()->json($workflows, 200);
        } else {
            return response()->json(403);
        }
    }
    public function getWorkflowProgress(Request $request)
    {
        $workflow = Workflow::find($request->id);

        if ($workflow) {
            $steps = Step::with(['workflow.form.creator', 'user'])
                ->where('workflow_id', $workflow->id)
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
        } else {
            return response()->json(['error' => 'Workflow not found'], 404);
        }
    }
    public function getWorkflowMembers(Request $request)
    {
        try {
            $step_id = Crypt::decryptString($request->step_key);
        } catch (DecryptException $e) {
            return response()->json(['error' => 'Invalid step key'], 404);
        }
        $step = Step::find($step_id);
        if ($step) {

            $workflow = $step->workflow;
            $usersInWorkflow = $workflow->steps->pluck('user');

            $usersRolesInWorkflow = $usersInWorkflow->flatMap(function ($user) {
                return $user->getRoleNames();
            });

            // Get all users with the same roles
            $allUsersWithSameRoles = User::role($usersRolesInWorkflow->unique()->all())->get();
            return response()->json($allUsersWithSameRoles, 200);
        } else
            return response()->json(['error' => 'Step not found or unauthorized'], 404);

    }
    public function form_approve(Request $request)
    {
        try {
            $step_id = Crypt::decryptString($request->step_key);
        } catch (DecryptException $e) {
            return response()->json(['error' => 'Invalid step key'], 404);
        }

        $step = Step::find($step_id);

        if ($step && $step->user->id === auth()->user()->id) {
            $step->status = '2';
            $step->review = 'Approved';
            $step->save();

            $this->nextStep($step);

            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => 'Step not found or unauthorized'], 404);
        }
    }
    public function form_forward(Request $request)
    {
        try {
            $step_id = Crypt::decryptString($request->step_key);
        } catch (DecryptException $e) {
            return response()->json(['error' => 'Invalid step key'], 404);
        }

        $step = Step::find($step_id);
        if ($step) {
            DB::beginTransaction();
            try {
                $step->status = 4;
                $step->review = "Forwarded";
                $step->save();
                $newStepNumber = $step->step + 1;

                Step::where('workflow_id', $step->workflow_id)
                    ->where('step', '>=', $newStepNumber)
                    ->increment('step');

                $newStep = Step::create([
                    'workflow_id' => $step->workflow_id,
                    'user_id' => $request->user_id,
                    'step' => $newStepNumber,
                    'forwarded_from' => $step->id,
                    'status' => 1, // 1 => pending
                ]);

                // Notify the newly assigned user about the forwarded form
                $message = "You received a forwarded form that needs your action";
                $newStep->user->notify(new FormReceived(auth()->user()->id, $message, $step->workflow->form->id, $newStep->id));

                // Commit the transaction
                DB::commit();

                return response()->json(['message' => 'Form forwarded successfully.'], 200);
            } catch (\Exception $e) {
                // Rollback the transaction in case of an error
                DB::rollback();

                return response()->json(['error' => 'An error occurred while forwarding the form.'], 500);
            }
        }
    }
    public function nextStep(Step $step)
    {
        $workflow = $step->workflow;

        if ($step->forwarded_from) {

            $prevouseStep = Step::find($step->forwarded_from);
            // Notify the user who forwarded the form
            $PreUser = User::find($prevouseStep->user_id);
            $message = "The form you forwarded was approved and needs your action";
            $PreUser->notify(new FormReceived($workflow->creator, $message, $workflow->form->id, $prevouseStep->id));
            return;
        }

        $lastStep = $workflow->steps()->whereNull('forwarded_from')->orderBy('step', 'desc')->first();

        if ($lastStep && $lastStep->step == $step->step) {
            $workflow->status = 2;
            $workflow->save();
            $message = 'Your workflow is over!';
            $workflow->creator->notify(new FormCompletion($workflow, $message));
            return;
        } else {
            $nextStep = $workflow->steps()->where('step', '>', $step->step)->whereNull('forwarded_from')->orderBy('step')->first();
            if ($nextStep) {
                Step::find($nextStep->id)->update([
                    'status' => 1,
                ]);
                // Notify the next recipient
                $nextRecipient = User::find($nextStep->user_id);
                $message = "You received a form that needs your action";
                $nextRecipient->notify(new FormReceived($workflow->creator, $message, $workflow->form->id, $nextStep->id));
            }
        }
    }
    public function form_reject(Request $request)
    {
        try {
            $step_id = Crypt::decryptString($request->step_key);
        } catch (DecryptException $e) {
            return response()->json(['error' => 'Invalid step key'], 404);
        }

        $step = Step::find($step_id);

        if ($step && $step->user->id === auth()->user()->id) {
            // Update the step status to "rejected"
            $step->status = 3;
            $step->review = $request->review;
            $step->save();

            // Update the workflow status to "rejected"
            $workflow = $step->workflow;
            $workflow->status = 2;
            $workflow->save();

            // Notify the workflow creator
            $message = 'Your form has been rejected.';
            $workflow->creator->notify(new FormCompletion($workflow, $message));

            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => 'Step not found or unauthorized'], 404);
        }
    }
    public function form_return(Request $request)
    {
        try {
            $step_id = Crypt::decryptString($request->step_key);
        } catch (DecryptException $e) {
            return response()->json(['error' => 'Invalid step key'], 404);
        }

        $step = Step::find($step_id);

        if ($step) {
            DB::beginTransaction();

            try {
                // Save the details of the current step (mark as returned)
                $step->status = 5;
                $step->review = $request->review;
                $step->isReturened = 1;
                $step->save();



                // Create a new step for the previous user (increment step number by 2)
                $prevStepNumber = $step->step + 1;
                Step::where('workflow_id', $step->workflow_id)
                    ->where('step', '>=', $prevStepNumber)
                    ->increment('step', 2);

                // Create a new step for the current user (increment step number by 2)
                $newStepNumber = $step->step + 2;
                Step::create([
                    'workflow_id' => $step->workflow_id,
                    'user_id' => $step->user_id,
                    'step' => $newStepNumber,
                    'status' => 0,  // Set the status to pending for the current user
                ]);

                $prevStep = Step::where('workflow_id', $step->workflow_id)
                    ->where('step', $step->step - 1)
                    ->first();

                $prevStepUser = $prevStep->user->id;
                $prevStep = Step::create([
                    'workflow_id' => $step->workflow_id,
                    'user_id' => $prevStepUser,
                    'step' => $prevStepNumber,
                    'status' => 1,  // Set the status to pending for the previous user
                ]);

                // Notify the previous user about the returned form
                $message = "The form you approved has been returned and requires your action";
                $prevStep->user->notify(new FormReceived(auth()->user()->id, $message, $step->workflow->form->id, $prevStep->id));

                // Commit the transaction
                DB::commit();

                return response()->json(['message' => 'Form returned successfully.'], 200);
            } catch (\Exception $e) {
                // Rollback the transaction in case of an error
                DB::rollback();

                return response()->json(['error' => 'An error occurred while returning the form.'], 500);
            }
        }
    }

}
