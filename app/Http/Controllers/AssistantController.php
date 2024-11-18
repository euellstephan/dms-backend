<?php

namespace App\Http\Controllers;

use App\Models\Assistant;
use Illuminate\Http\Request;
use App\Http\Resources\AssistantResource;
use App\Http\Resources\AssistantCollection;
use App\Http\Resources\ResponseResource;
use Illuminate\Support\Facades\Auth;

class AssistantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       

        $assistants = new AssistantCollection(Assistant::with('residence')->paginate(10));

        return response()->json([
            'assistants' => $assistants,
            'status' => true,
            'message' => 'Assitances fetched successfully'
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

        $user = Auth::user();
        $resident = $user->residence;

        if (!$resident) {
            return response()->json(['error' => 'Resident not found for the logged-in user.'], 404);
        }

        $request->validate([
            'assitant_type' => 'required|string',
            'description' => 'required|string',
            'date_request' => 'required|date',
            'status' => 'required|string',
            'lat' => 'required|string',
            'lng' => 'required|string',
        ]);


        $assistant = new Assistant([
            'assitant_type' => $request->assitant_type,
            'description' => $request->description,
            'date_request' => $request->date_request,
            'status' => $request->status,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'resident_id' => $resident->id
        ]);

        $assistant->save();
        
        return response()->json([
            'assistant' => new AssistantResource($assistant),
            'status' => true,
            'message' => 'Assitance created successfully'
        ], 201);



    }

    /**
     * Display the specified resource.
     */
    public function show(Assistant $assistant)
    {
        return response()->json([
            'assistant' => new AssistantResource($assistant),
            'status' => true,
            'message' => 'Assitance fetched successfully'
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assistant $assistant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Assistant $assistant)
    {
        $request->validate([
            'assitant_type' => 'sometimes|string',
            'description' => 'sometimes|string',
            'date_request' => 'sometimes|date',
            'status' => 'sometimes|string',
            'lat' => 'sometimes|string',
            'lng' => 'sometimes|string',
        ]);

        $assistant->update($request->only(['assitant_type', 'description', 'date_request', 'status', 'lat', 'lng']));

        return response()->json([
            'assistant' => new AssistantResource($assistant),
            'status' => true,
            'message' => 'Assitance updated successfully'
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assistant $assistant)
    {
        $assistant->delete();

        return response()->json([
            'status' => true,
            'message' => 'Assitance deleted successfully'
        ], 200);
    }
}
