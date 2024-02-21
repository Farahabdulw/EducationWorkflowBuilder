<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Committe;
use DB;

class CommitteController extends Controller
{
    public function index()
    {
        return view('content.pages.committees.committees');
    }

    public function add()
    {
        return view('content.pages.committees.committees-add');
    }

    public function addCom(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'cname' => 'required',
            'chairperson' => 'required',
            'description' => 'max:255',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new committe record in the database
        $committe = Committe::create([
            'name' => $request->get('cname'),
            'chairperson' => $request->get('chairperson'),
            'description' => $request->get('description'),
        ]);

        // Return a success response
        if ($committe) {
            $this->addToGroupsAffiliations($committe->id, $committe->name);
            return response()->json(['success' => true, 'message' => 'Committe added successfully'], 200);
        } else
            return response()->json([
                'success' => false,
                'error' => $committe,
            ], 422);
    }
    public function get_committees()
    {
        if (auth()->user()->hasRole('super-admin')) {
            $canEdit = true;
            $canDelete = true;
            $canAdd = true;
            $committees = Committe::query()
                ->with([
                        'chairpersonUser' => function ($query) {
                            $query->select('id', 'first_name', 'last_name');
                        }
                    ])->get();
        } else {
            $authUser = auth()->user();
            $authUserRoles = $authUser->roles;
            $canEdit = auth()->user()->can('committees_edit');
            $canDelete = auth()->user()->can('committees_delete');
            $canAdd = auth()->user()->can('committees_add');

            $groupsAff = $authUser->groups->pluck('affiliations')->map(function ($affiliations) {
                $affiliationsArray = json_decode($affiliations, true);
                return $affiliationsArray['committees'] ?? [];
            })->flatten();

            // Assuming $committees is the collection of all committees
            $committees = Committe::whereIn('id', $groupsAff->toArray())->with([
                'chairpersonUser' => function ($query) {
                    $query->select('id', 'first_name', 'last_name');
                }
            ])->get();
        }
        $responseObject = [
            'committees' => $committees,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete,
            'canAdd' => $canAdd,
        ];
        return response()->json($responseObject, 200);
    }
    public function get_committee($id)
    {
        $committee = Committe::find($id);

        if (!$committee) {
            return response()->json(['error' => 'Committee not found'], 404);
        }

        $formattedUser = [
            'id' => $committee->id,
            'name' => $committee->name,
            'chairperson' => $committee->chairperson,
            'description' => $committee->description,
        ];

        return response()->json($formattedUser, 200);
    }

    public function delete(Request $request)
    {
        $committee = Committe::find($request->id);

        if (!$committee) {
            return response()->json(['error' => "Committee not found $request->id "], 404);
        }
        $committee->delete();

        return response()->json(['success' => true, 'message' => 'Committee soft deleted successfully'], 200);
    }
    public function edit_committee(Request $request)
    {
        $committee = Committe::with('chairpersonUser')->find($request->id);

        if (!$committee)
            return response()->json(['error' => 'committee not found'], 404);

        $committee->name = $request->name;
        $committee->chairperson = $request->chairperson;
        $committee->description = $request->description;
        $committee->save();

        return response()->json(['message' => 'committee updated successfully', 'committee' => $committee], 200);
    }
    public function addToGroupsAffiliations($affiliationId, $affiliationName, $type = 'committees')
    {
        $roles = auth()->user()->roles->pluck('name')->toArray();
        $groups = \App\Models\Groups::whereIn('name', $roles)->get();

        foreach ($groups as $group) {
            $affiliations = json_decode($group->affiliations, true);

            if (!isset($affiliations[$type])) {
                $affiliations[$type] = [];
            }

            $affiliations[$type][] = ["id" => $affiliationId, "name" => $affiliationName];

            $group->affiliations = json_encode($affiliations);
            $group->save();
        }
    }

}
