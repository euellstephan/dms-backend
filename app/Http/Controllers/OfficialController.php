<?php

namespace App\Http\Controllers;

use App\Models\Official;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\OfficialCollection;
use App\Http\Resources\OfficialResource;
use App\Http\Resources\UserResource;

class OfficialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $officials = new OfficialCollection(Official::with('user')->paginate(10));

        return response()->json([
            'officials' => $officials,
            'status' => true,
            'message' => 'Users fetched successfully'
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'role' => 'required|string|in:resident,official',


            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'date_of_birth' => 'required|date',
            'age' => 'required|integer',
            'address' => 'required|string',
            'phone_number' => 'required|string',
            'gender' => 'required|string',
            'position' => 'required|string'
        ]);


        // Create the user
        $user = new User([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => "official"
        ]);

        $user->save();

        $official = new Official([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'age' => $request->age,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'gender' => $request->gender,
            'position' => $request->position
        ]);

        $user->official()->save($official);


        return response()->json([
            'message' => 'Official and user created successfully!',
            'user' => new UserResource($user),
            'official' => new OfficialResource($official)
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Official $official)
    {
        return response()->json([
            'official' => new OfficialResource($official),
            'status' => true,
            'message' => 'Official fetched successfully'
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Official $official)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Official $official)
    {
          $request->validate([
            // user fields
            'name' => 'sometimes|string',
            'username' => 'sometimes|string|unique:users,username,' . $official->user_id,
            'email' => 'sometimes|email|unique:users,email,' . $official->user_id,
            'password' => 'sometimes|string|confirmed|min:6',
            'role' => 'sometimes|string|in:resident,official',

            'first_name' => 'sometimes|string',
            'last_name' => 'sometimes|string',
            'date_of_birth' => 'sometimes|date',
            'age' => 'sometimes|integer',
            'address' => 'sometimes|string',
            'phone_number' => 'sometimes|string',
            'gender' => 'sometimes|string',
            'position' => 'sometimes|string'
        ]);

        $user = $official->user;
        $userData = $request->only(['name', 'username', 'email', 'password', 'role']);
        
        if(isset($userData['password'])) {
            $userData['password'] = bcrypt($userData['password']);
        }

        $user->update($userData);

        $official->update(
            $request->only(
                [
                    'first_name', 
                    'last_name', 
                    'date_of_birth', 
                    'age', 
                    'address', 
                    'phone_number', 
                    'gender', 
                    'position'
                ]
            )
        );

       
        return response()->json([
            'message' => 'Official updated successfully!',
            'user' => new UserResource($user),
            'official' => new OfficialResource($official)
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Official $official)
    {
        $official->delete();
        
        return response()->json([
            'message' => 'Official deleted successfully!'
        ], 200);
    }
}
