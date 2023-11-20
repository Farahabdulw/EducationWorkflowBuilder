<?php

namespace App\Http\Controllers;

use App\Notifications\FormReceived;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Workflow;
use App\Models\Step;

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
        ]);

        foreach ($users as $index => $user) {
            // Create a step for each user in the workflow
            $step = Step::create([
                'workflow_id' => $workflow->id,
                'user_id' => $user['id'],
                'step' => $index + 1,
                'status' => 0, // 0 => pending , 1 => aproved , 2 => rejected , 3 => forwarded
            ]);
        }
        $this->lunchWorkflow($workflow, $sender_id ,$form_id);
        return response()->json("succsful", 200);

    }
    public function lunchWorkflow(Workflow $workflow, int $sender_id , $form_id)
    {
        $workflow->status = 1;
        $workflow->save();
        $firstStep = $workflow->steps()->orderBy('step')->first();

        $sender = User::find($sender_id);
        $recipient = User::find($firstStep->user_id);

        $message = "You received a form from {$sender->first_name} {$sender->last_name} that needs your action";

        // Notify the recipient
        $recipient->notify(new FormReceived($sender_id, $message , $form_id , $firstStep->id));
    }
}
