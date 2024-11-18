<?php

namespace App\Http\Controllers;

use App\Models\Residence;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\ResidenceCollection;
use App\Http\Resources\ResidenceResource;
use App\Http\Resources\UserResource;
class ResidenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $residense = new ResidenceCollection(Residence::with('user')->paginate(10));

        return response()->json([
            'residence' => $residense,
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
            'phone_number' => 'required|string|max:15',
            'gender' => 'required|string'
        ]);

        // Create the user
        $user = new User([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => "resident"
        ]);

        $user->save();


        $residence = new Residence([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'age' => $request->age,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'gender' => $request->gender
        ]);

        $user->residence()->save($residence);

     

        return response()->json([
            'message' => 'Residence and user created successfully!',
            'user' => new UserResource($user),
            'residence' => new ResidenceResource($residence)
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Residence $residence)
    {
        return response()->json([
            'residence' => new ResidenceResource($residence),
            'status' => true,
            'message' => 'Residence fetched successfully'
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Residence $residence)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Residence $residence)
    {

        $request->validate([
            // user fields
            'name' => 'sometimes|string',
            'username' => 'sometimes|string|unique:users,username,' . $residence->user_id,
            'email' => 'sometimes|email|unique:users,email,' . $residence->user_id,
            'password' => 'sometimes|string|confirmed|min:6',
            'role' => 'sometimes|string|in:resident,official',

            'first_name' => 'sometimes|string',
            'last_name' => 'sometimes|string',
            'date_of_birth' => 'sometimes|date',
            'age' => 'sometimes|integer',
            'address' => 'sometimes|string',
            'phone_number' => 'sometimes|string',
            'gender' => 'sometimes|string'
        ]);


        // Update the user
        $user = $residence->user;
        $userData = $request->only(['name', 'username', 'email', 'password', 'role']);

        if (isset($userData['password'])) {
            $userData['password'] = bcrypt($userData['password']);
        }

        $user->update($userData);

        // Update the residence
        $residence->update($request->only([
            'first_name',
            'last_name',
            'date_of_birth',
            'age',
            'address',
            'phone_number',
            'gender'
        ]));


        return response()->json([
            'message' => 'Residence and user updated successfully!',
            'user' => new UserResource($user),
            'residence' => new ResidenceResource($residence)
        ], 200);


      
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Residence $residence)
    {
        $residence->delete();

        return response()->json([
            'message' => 'Residence deleted successfully!'
        ], 200);
    }
}
