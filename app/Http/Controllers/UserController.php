<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;

use App\Models\User;


class UserController extends Controller
{
    public function index()
    {
        if (auth()->user()->can('users_view'))
            return view('content.pages.users');
        else
            return view('403');
    }
    public function addForm()
    {

        // Display the HTML form for adding a user
        if (auth()->user()->can('users_add'))
            return view('content.pages.user-add');
        else
            return view('403');
    }
    public function createUser(Request $request)
    {
        // Validate and add the user to the database
        $validator = Validator::make($request->all(), [
            'fname' => 'required',
            'lname' => 'required',
            'uni_id' => 'required',
            'birthdate' => 'required|date',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'groups' => 'array',
        ]);

        if ($validator->fails()) {
            // Validation failed
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new user record in the database
        $user = User::create([
            'first_name' => $request->get('fname'),
            'last_name' => $request->get('lname'),
            'uni_id' => $request->get('uni_id'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'birthdate' => $request->get('birthdate'),
        ]);
        // add the newly created user to the each group 

        if (isset($request->groups)) {
            $user->groups()->sync($request->groups);
            // Sync roles based on the groups
            $groupNames = $user->groups->pluck('name');
            $roles = Role::whereIn('name', $groupNames)->get();

            // Sync roles to the user
            $user->syncRoles($roles);
        }
        if ($user)
            return response()->json(['success' => true, 'message' => 'User added successfully'], 200);
        else
            return response()->json([
                'success' => false,
                'error' => $user,
            ], 422);
    }
    public function get_users()
    {
        // $user = auth()->user()->roles;
        $canEdit = false;
        $canDelete = false;
        $canAdd = false;
        $formattedUsers = [];
        if (auth()->user()->hasRole('super-admin')) {
            $canEdit = true;
            $canDelete = true;
            $canAdd = true;
            $users = User::all();
            foreach ($users as $user) {
                $birthdate = Carbon::parse($user->birthdate);
                $age = $birthdate->age;
                $formattedUser = [
                    'id' => $user->id,
                    'fname' => $user->first_name,
                    'email' => $user->email,
                    'uni_id' => $user->uni_id,
                    'lname' => $user->last_name,
                    'age' => $age,
                    'can-edit' => true,
                    'delete-edit' => true,
                ];
                $formattedUsers[] = $formattedUser;
            }
        } else {
            // Get authenticated user's roles
            $authUser = auth()->user();
            $authUserRoles = $authUser->roles;
            $authUserGroups = $authUser->groups;
            $canEdit = $authUserRoles->contains(function ($role) {
                return $role->hasPermissionTo('users_edit');
            });

            $canDelete = $authUserRoles->contains(function ($role) {
                return $role->hasPermissionTo('users_delete');
            });

            $canAdd = $authUserRoles->contains(function ($role) {
                return $role->hasPermissionTo('users_add');
            });
            $GroupsIds = $authUserGroups->pluck('id');
            $users = User::with("groups")->whereHas("groups", function ($q) use ($GroupsIds) {
                $q->whereIn("groups.id", $GroupsIds);
            })->get();

            // Exclude the authenticated user from the list
            $users = $users->reject(function ($user) use ($authUser) {
                return $user->id === $authUser->id;
            });

            // Iterate through each user
            foreach ($users as $user) {
                // Calculate age using Carbon
                $birthdate = Carbon::parse($user->birthdate);
                $age = $birthdate->age;

                // Build user information array
                $userInfo = [
                    'id' => $user->id,
                    'fname' => $user->first_name,
                    'uni_id' => $user->uni_id,
                    'email' => $user->email,
                    'lname' => $user->last_name,
                    'age' => $age,
                ];

                // Add user information to the formattedUsers array
                $formattedUsers[] = $userInfo;
            }
        }
        $responseObject = [
            'users' => $formattedUsers,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete,
            'canAdd' => $canAdd,
        ];
        return response()->json($responseObject, 200);
    }
    public function get_user($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $formattedUser = [
            'id' => $user->id,
            'fname' => $user->first_name,
            'email' => $user->email,
            'lname' => $user->last_name,
            'uni_id' => $user->uni_id,
            'birthdate' => $user->birthdate,
        ];

        return response()->json($formattedUser, 200);
    }

    public function edit_user(Request $request)
    {
        $user = User::find($request->id);

        if (!$user)
            return response()->json(['error' => 'User not found'], 404);

        $user->first_name = $request->fname;
        $user->last_name = $request->lname;
        $user->uni_id = $request->uni_id;
        $user->email = $request->email;
        $user->birthdate = $request->birthdate;
        $user->save();

        return response()->json(['message' => 'User updated successfully'], 200);
    }

    public function delete(Request $request)
    {
        $user = User::find($request->id);

        if (!$user) {
            return response()->json(['error' => "User not found $request->id "], 404);
        }
        $user->delete();

        return response()->json(['success' => true, 'message' => 'User soft deleted successfully'], 200);
    }
    public function get_current_user()
    {
        return response()->json(auth()->user()->id, 200);

    }
}

