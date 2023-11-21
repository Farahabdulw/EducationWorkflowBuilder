<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Center;
use App\Models\College;
use App\Models\Committe;
use App\Models\User;
use App\Models\Groups;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class GroupsController extends Controller
{
    public function createUserGroup()
    {
        // Display the HTML form for adding a user
        return view('content.pages.user.add-user-groups');
    }
    public function get_affiliations()
    {
        $data = new \stdClass();
        $data->users = User::select('id', 'first_name', 'last_name')->get();
        $data->affiliations = new \stdClass();

        $data->affiliations->committees = Committe::select('id', 'name')->get();
        $data->affiliations->centers = Center::select('id', 'name')->get();
        $data->affiliations->departments = Department::select('id', 'name')->get();
        $data->affiliations->colleges = College::select('id', 'name')->get();

        return response()->json($data, 200);
    }
    public function get_groups()
    {
        $user = auth()->user();
        if ($user->hasRole('super-admin')) {
            $groups = Groups::get();
            return response()->json($groups, 200);

        } else {
            $userRoles = $user->roles->pluck('name')->toArray();
            $groups = Groups::whereIn('name', $userRoles)->get();

            // Return the groups as JSON response
            return response()->json($groups, 200);
        }
    }
    public function get_groups_members(Request $request)
    {
        $groupIds = $request->groupsIDs;
        if (!$groupIds)
            return response()->json([], 200);


        $users = User::whereHas('groups', function ($query) use ($groupIds) {
            $query->whereIn('groups.id', $groupIds);
        })->get();

        return response()->json($users, 200);
    }
    public function get_group($id)
    {
        $group = Groups::with('users')->find($id);
        if ($group) {
            return response()->json($group, 200);
        } else
            return response()->json('group not found', 404);

    }
    public function addUsersGroup(Request $request)
    {
        // Validate and add the user to the database
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'permissions' => 'required|json',
            'users' => 'array',
            'affiliation' => 'required|json',
        ]);


        if ($validator->fails()) {
            // Validation failed
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new user record in the database
        $group = Groups::create([
            'name' => $request->get('name'),
            'affiliations' => $request->get('affiliation'),
            'permissions' => $request->get('permissions'),
        ]);
        $role = Role::create(['name' => $group->name]);
        $permissions = json_decode($request->permissions, true);

        foreach ($permissions as $entity => $actions) {
            foreach ($actions as $action) {
                $permissionName = $entity . '_' . $action;

                // Check if the permission already exists
                $existingPermission = Permission::where('name', $permissionName)->first();

                // Create the permission if it doesn't exist
                if (!$existingPermission) {
                    $existingPermission = Permission::create(['name' => $permissionName]);
                }

                // Attach the permission to the role without duplicating
                $role->givePermissionTo($existingPermission);
            }
        }

        if ($group && $request->has('users')) {
            foreach ($request->input('users') as $id) {
                $user = User::find($id);
                $user->roles()->syncWithoutDetaching([$role->id]);
            }
            $group->users()->sync($request->input('users'));
        }

        // Return a success response
        if ($group)
            return response()->json(['success' => true, 'message' => 'A user group added successfully'], 200);
        else
            return response()->json([
                'success' => false,
                'error' => $group,
            ], 422);
    }

    public function editUsersGroup($id)
    {
        return view('content.pages.user.add-user-groups');
    }
    public function updateUsersGroup(Request $request)
    {
        $group = Groups::find($request->id);

        if (!$group)
            return response()->json(['error' => 'Group not found'], 404);


        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'users' => 'array',
            'affiliation' => 'required|json',
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);


        if ($group && $request->has('users')) {
            $group->users()->sync($request->input('users'));
        }

        $group->name = $request->name;
        $group->affiliations = $request->affiliation;

        $group->save();

        $role = Role::where('name', $group->name)->first();

        if ($role) {
            $role->users()->sync([]);
            foreach ($request->users as $userId) {
                $user = User::find($userId);
                $user->roles()->syncWithoutDetaching([$role->id]);
            }
        }
        return response()->json(['success' => true, 'message' => 'Group has been updated successfully'], 200);
    }

    public function edit_groups_permissions(Request $request)
    {
        $group = Groups::find($request->id);

        if (!$group) {
            return response()->json(['error' => 'Group not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'permissions' => 'required|json',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $group->permissions = $request->permissions;
        $group->save();

        $role = Role::where('name', $group->name)->first();

        if ($role) {
            // Detach existing permissions
            $role->permissions()->detach();

            // Attach new permissions
            $permissions = json_decode($request->permissions, true);
            foreach ($permissions as $entity => $actions) {
                foreach ($actions as $action) {
                    $permissionName = $entity . '_' . $action;

                    // Check if the permission already exists
                    $existingPermission = Permission::where('name', $permissionName)->first();

                    // Create the permission if it doesn't exist
                    if (!$existingPermission) {
                        $existingPermission = Permission::create(['name' => $permissionName]);
                    }

                    // Attach the permission to the role
                    $role->givePermissionTo($existingPermission);
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Groups permissions updated successfully'], 200);
    }


}
