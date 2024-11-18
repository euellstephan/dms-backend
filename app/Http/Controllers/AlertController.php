<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\Request;
use App\Http\Resources\AlertResource;
use App\Http\Resources\AlertCollection;
use Illuminate\Support\Facades\Auth;

class AlertController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alerts = new AlertCollection(Alert::with('official')->paginate(10));
      
        return response()->json([
            'alerts' => $alerts,
            'status' => true,
            'message' => 'Services fetched successfully'
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
        // Define validation rules


        $user = Auth::user();
        $official = $user->official;


        if (!$official) {
            return response()->json(['error' => 'Official not found for the logged-in user.'], 404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
        ]);

        // Create a new alert
        $alert = new Alert([
            'official_id' => $official->id,
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
        ]);

        // Save the alert
        $alert->save();

        // Return a response
        return response()->json([
            'alert' => new AlertResource($alert),
            'status' => true,
            'message' => 'Alert created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Alert $alert)
    {
        return response()->json([
            'alert' => new AlertResource($alert),
            'status' => true,
            'message' => 'Alert retrieved successfully'
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alert $alert)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alert $alert)
    {
          // Define validation rules
        $request->validate([
            'official_id' => 'sometimes|exists:officials,id',
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'category' => 'sometimes|string|max:255',
        ]);

        // Update the alert
        $alert->update($request->only(['official_id', 'title', 'description', 'category']));

        // Return a response
        return response()->json([
            'alert' => new AlertResource($alert),
            'status' => true,
            'message' => 'Alert updated successfully'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alert $alert)
    {
        $alert->delete();

        return response()->json([
            'status' => true,
            'message' => 'Alert deleted successfully'
        ], 200);
    }
}
