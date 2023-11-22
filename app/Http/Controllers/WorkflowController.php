<?php

namespace App\Http\Controllers;

use App\Notifications\FormReceived;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Workflow;
use App\Models\Step;
use App\Models\Forms;

class WorkflowController extends Controller
{
    public function create(Request $request)
    {
        $users = $request->users;
        $form_id = $request->form;
        $sender_id = $request->sender_id;
        $workflow = Workflow::create([
            "forms_id" => $form_id,
            "status" => 0, // 0 => pending , 1 => in progress , 2 => completed/over
            "created_by" => $sender_id, 
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
        return response()->json("succsful", 200);

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
                        $query->select('id', 'first_name' , 'last_name');
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
                ->get();
    
            $progress = $steps->map(function ($step) {
                return [
                    'id' => $step->id,
                    'status' => $step->status,
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
    
}
