<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Resources\OfficialResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\ServiceCollection;


class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = new ServiceCollection(Service::with('official')->paginate(10));
      
         return response()->json([
            'services' => $services,
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
        $user = Auth::user();
        $official = $user->official;

        if (!$official) {
            return response()->json(['error' => 'Official not found for the logged-in user.'], 404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'eligibility' => 'required|string',
            'category' => 'required|string',
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
            'status' => 'required|in:0,1',
        ]);

        // Create a new service
        $service = new Service([
            'official_id' => $official->id,	
            'title' => $request->title,
            'description' => $request->description,
            'eligibility' => $request->eligibility,
            'category' => $request->category,
            'date_start' => $request->date_start,
            'date_end' => $request->date_end,
            'status' => $request->status,
        ]);
        // Save the service
        $service->save();

        // Return a response
        return response()->json([
            'service' => new ServiceResource($service->load('official')),
            'status' => true,
            'message' => 'Service created successfully'
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        return response()->json([
            'service' => new ServiceResource($service->load('official')),
            'status' => true,
            'message' => 'Service retrieved successfully'
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        // Define validation rules
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'eligibility' => 'sometimes|string',
            'category' => 'sometimes|string',
            'date_start' => 'sometimes|date',
            'date_end' => 'sometimes|date|after_or_equal:date_start',
            'status' => 'sometimes|in:0,1',
        ]);

        // Update the service
        $service->update($request->only([
            'title',
            'description',
            'eligibility',
            'category',
            'date_start',
            'date_end',
            'status',
        ]));

        // Return a response
        return response()->json([
            'service' => new ServiceResource($service->load('official')),
            'status' => true,
            'message' => 'Service updated successfully'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        // Delete the service
        $service->delete();

        // Return a response
        return response()->json([
            'status' => true,
            'message' => 'Service deleted successfully'
        ], 200);
    }

    
}
