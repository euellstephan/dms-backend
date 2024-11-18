<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;


class AuthController extends Controller
{
    //

    public function users(){
        $usersData = new UserCollection(User::all());

        return response()->json([
            'status' => true,
            'users' => $usersData,
            'message' => 'Users fetched successfully'
        ], 200);
    }


    public function register(Request $request)
    {
       $request->validate([
        'name' => 'required|string',
        'username' => 'required|string|unique:users',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|confirmed|min:6',
        'role' => 'sometimes|string' // Optional role field
    ]);

    $user = new User([
        'name' => $request->name,
        'username' => $request->username,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role' => $request->role ?? 'user' // Default to 'user' if not provided
    ]);

    $user->save();
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Successfully created user!',
        'token' => $token,
        'user' => $user
    ], 201);
    }

  public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string', // Accept either username or email
            'password' => 'required|string'
        ]);

        // Attempt to find the user by email or username
        $user = User::where('email', $request->login)
                    ->orWhere('username', $request->login)
                    ->first();


        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Bad credentials'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ], 201);
    }

    public function userProfile(){
        $userData = new UserResource(User::findOrFail(auth()->user()->id));

        // $userData = auth()->user();
        return response()->json([
            'status' => true,
            'user' => $userData,
            'message' => 'User profile fetched successfully',
            'id' => auth()->user()->id,
        ], 200); // Return user data

    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        // auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out',
            'status' => true,
            'data' => []
        ], 200);
    }


}
