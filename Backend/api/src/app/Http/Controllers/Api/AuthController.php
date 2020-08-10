<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\PasswordReset;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Laravel\Passport\Passport;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedDate = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'username' => 'required|string|alpha_dash|max:255|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'password_confirmation' => 'required|string|min:6',
        ]);

        $validatedDate['password'] = bcrypt($validatedDate['password']);
        $validatedDate['password_confirmation'] = bcrypt($validatedDate['password_confirmation']);

        $user = new User($validatedDate);

        $role = $user->assignRole('user');
        if ($user->save()) {
            $accessToken = $user->createToken('authToken')->accessToken;
            return response()->json([
                'successfully_message' => "The user created successfully",
                'isLogin'=>true,
                "user" => new UserResource($user),
                'token_type' => 'Bearer',
                "access_token" => $accessToken,
            ], 201);
        }

        return response()->json([
            'error_message' => 'Some problems happened in the server.'
        ], 500);

    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'isLogin'=>false,
                'errors' => [
                    'auth' => array( 'The email or password is incorrect.')
                ]
            ], 401);
        }
        $user = Auth::user();

        if ($request->remember_me) {
            Passport::personalAccessTokensExpireIn(now()->addHours(3));
        } else {
            Passport::personalAccessTokensExpireIn(now()->addHours(1));
        }

        $tokenResult = $user->createToken('authToken');
        $token = $tokenResult->token;
        $token->save();

        return response()->json([
            'isLogin'=>true,
            'user' => new UserResource($user),
            'token_type' => 'Bearer',
            'access_token' => $tokenResult->accessToken,
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'successfully_message' => 'Successfully logged out',
            'isLogin'=>false
        ], 200);
    }

    public function passwordReset(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'changePassword_token' => 'required|string'
        ]);

        $user = User::where('email', $request['email'])->first();
        $isCreated = false;

        if ($user) {

            PasswordReset::where('user_id', $user->id)->delete();

            $token = $validatedData['changePassword_token'];
            $expiry_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $passwordRestData = [
                'user_id' => $user->id,
                'token' => $token,
                'expires_at' => $expiry_at,
            ];

            $password_reset = new PasswordReset($passwordRestData);

            $password_reset->save();

            $isCreated = true;
        }
        return response()->json([
            'successfully_message' =>
                'Please check your email for the password reset link.' .
                ' We have sent a password reset email to the following email address: ' . $validatedData['email'],
            'user_email' => $validatedData['email'],
            'isCreated' => $isCreated,
        ], 200);
    }

    public function changePasswordResetLink(Request $request)
    {
        $validatedDate = $request->validate([
            'token' => 'required|string',
            'password' => 'required|string|confirmed|min:6',
            'password_confirmation' => 'required|string|min:6',
        ]);

        $now = date('Y-m-d H:i:s');
        $statement = new PasswordReset();
        $statement = PasswordReset::where('token', $validatedDate['token']);
        $statement = $statement->where('expires_at', '>', $now)->first();

        if (!$statement) {
            return response()->json([
                'error_message' => 'The token is not valid. Please try again '
            ], 401);
        }

        $statement->user['password'] = bcrypt($validatedDate['password']);
        $statement->user->update();
        $statement->delete();
        return response()->json([
            'successfully_message' => 'Your Password has been changed, Please log in!'
        ], 200);
    }

    public function user(Request $request)
    {
        $user = auth()->user();

        if($user){
            return response()->json([
                'user' => new UserResource($user),
                'isLogin'=>true,
            ], 200);
        }else
        return response()->json([
            'user' => new UserResource($user),
            'isLogin'=>false,
        ], 200);
    }

    public function isChangePasswordTokenValid(Request $request)
    {
        $validatedDate = $request->validate([
            'token' => 'required|string',
        ]);

        $now = date('Y-m-d H:i:s');
        $statement = new PasswordReset();
        $statement = PasswordReset::where('token', $validatedDate['token']);
        $statement = $statement->where('expires_at', '>', $now)->first();

        if (!$statement) {
            return response()->json([
                'error_message' => 'The token is not valid. Please try again ',
                'isTokenValid' => false
            ], 401);
        }

        return response()->json([
            'isTokenValid' => true
        ], 200);

    }
}
