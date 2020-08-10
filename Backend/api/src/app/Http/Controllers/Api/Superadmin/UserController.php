<?php

namespace App\Http\Controllers\Api\Superadmin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    private $exceptionType = 'superadmin';


    public function index()
    {
        $users = User::withTrashed()->paginate(25);
        return UserResource::collection($users);
    }

    public function store(Request $request)
    {
        $validatedDate = $request->validate([
            'name' => 'required|string',
            'username' => 'required|string|alpha_dash|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'type' => 'required|string|not_in:' . $this->exceptionType,
        ]);


        $validatedDate['password'] = bcrypt($validatedDate['password']);

        $user = new User($validatedDate);
        $accessToken = $user->createToken('authToken')->accessToken;

        $role = $user->assignRole($validatedDate['type']);
        if ($user->save()) {
            return response()->json([
                'successfully_message' => "The user created successfully",
                "user" => new UserResource($user),
                'token_type' => 'Bearer',
                "accessToken" => $accessToken
            ], 201);
        }

        return response()->json([
            'error_message' => 'Some problems happened in the server.'
        ], 500);

    }


    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(Request $request, User $user)
    {
        // if user is superadmin
        if ($user->id == auth()->id()) {
            $validatedDate = $request->validate([
                'name' => 'required|string',
                'username' => 'required|string|alpha_dash|max:255|unique:users,username,'.$user->id,
                'email' => 'required|email|unique:users,email,' . $user->id,
                'superadmin_password' => 'required|string|min:6',
            ]);

            if (!Hash::check($validatedDate['superadmin_password'], auth()->user()->password)) {
                return response()->json(['error_message' => 'Your current password is incorrect'], 401);
            }

            $user->name = $validatedDate['name'];
            $user->email = $validatedDate['email'];
            $user->username = $validatedDate['username'];

            if ($user->save()) {
                $request->user()->token()->revoke();
                return response()->json([
                    'successfully_message' => "updated successfully."
                ], 200);
            }

        } else {

            $validatedDate = $request->validate([
                'name' => 'required|string',
                'username' => 'required|string|alpha_dash|max:255|unique:users,username,'.$user->id
            ]);

            $user->name = $validatedDate['name'];
            $user->username = $validatedDate['username'];

            // Wenn die Änderung von Type gewünscht ist
            // aber man muss aufpassen alle mit diesem admin verbundenen city zu null
//            $user->syncRoles($validatedDate['type']);

            if ($user->save()) {
                // delete Token von User when the Eamil has changed
                return response()->json([
                    'successfully_message' => "updated successfully."
                ], 200);
            }
        }
        return response()->json([
            'error_message' => 'Some problems happened in the server.'
        ], 500);
    }

    public function destroy(User $user, Request $request)
    {
        $validatedDate = $request->validate([
            'superadmin_password' => 'required|string|min:6'
        ]);

        if ($user->hasRole($this->exceptionType)) {
            return response()->json([
                'error_message' => 'This user cannot be deleted.'
            ], 400);
        }


        // check admin password
        if (!Hash::check($validatedDate['superadmin_password'], auth()->user()->password)) {
            return response()->json(['error' => 'Your current admin password is incorrect'], 401);
        }

        if ($user->delete()) {
            return response()->json([
                'successfully_message' => "user softDeleted successfully."
            ], 200);
        }
        return response()->json([
            'error_message' => 'Some problems happened in the server.'
        ], 500);
    }


    public function updatePassword(Request $request)
    {
        $validatedDate = $request->validate([
            'password' => 'required|string|min:6',
            'new_password' => 'required|confirmed|string|min:6',
            'new_password_confirmation' => 'required|string|min:6',
        ]);

        $user = auth()->user();
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'error_message' => 'Your current super admin password is incorrect'
            ], 401);
        }

        $user->password = bcrypt($validatedDate['new_password']);

        if ($user->save()) {
            $request->user()->token()->revoke();
            return response()->json([
                'successfully_message' => "Password updated successfully."
            ], 200);
        }

        return response()->json([
            'error_message' => 'Some problems happened in the server.'
        ], 500);
    }

    public function restore(Request $request, $userId)
    {
        $validatedDate = $request->validate([
            'superadmin_password' => 'required|string|min:6',
        ]);

        // check admin password
        if (!Hash::check($validatedDate['superadmin_password'], auth()->user()->password)) {
            return response()->json(['error_message' => 'Your current admin password is incorrect'], 401);
        }

        $user = User::withTrashed()->findOrFail($userId);
        if ($user->restore()) {
            return response()->json([
                'successfully_message' => "user restored successfully."
            ], 200);
        }
        return response()->json([
            'error_message' => 'Some problems happened in the server.'
        ], 500);
    }

    public function indexTrashed()
    {
        $users = User::onlyTrashed();
        return UserResource::collection($users);
    }

    public function updateUserPassword(Request $request, User $user)
    {
        $validatedDate = $request->validate([
            'superadmin_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6',
        ]);

        // check admin password
        if (!Hash::check($validatedDate['superadmin_password'], auth()->user()->password)) {
            return response()->json(['error_message' => 'Your current admin password is incorrect'], 401);
        }

        if ($user->hasRole($this->exceptionType)) {
            return response()->json([
                'error_message' => 'This user cannot updated.'
            ], 400);
        }


        $user['password'] = bcrypt($validatedDate['new_password']);

        if ($user->save()) {
            $tokens = DB::table('oauth_access_tokens')->where('user_id', '=', $user->id);
            $tokens->update(['revoked' => 1]);
            return response()->json([
                'successfully_message' => "Password updated successfully."
            ], 200);
        }

        return response()->json([
            'error_message' => 'Some problems happened in the server.'
        ], 500);

    }
}
