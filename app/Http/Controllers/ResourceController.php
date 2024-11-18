<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;
use App\Http\Resources\ResourceResource;
use App\Http\Resources\ResourcesCollection;
use App\Http\Resources\OfficialResource;
use Illuminate\Support\Facades\Auth;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resources = new ResourcesCollection(Resource::with('official')->paginate(10));

        return response()->json([
            'resources' => $resources,
            'status' => true,
            'message' => 'Resources fetched successfully'
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
            'title' => 'required|string',
            'description' => 'required|string',
            'type' => 'required|string', // Corrected the validation rule for 'type'
            'file' => 'required|mimes:jpeg,png,jpg,gif,svg,mp4,mov,avi|max:20480', // Validate image or video
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $fileName, 'public');
            $fileMimeType = $file->getMimeType(); 
            $request->file_path = $filePath;
        }
        $resource = new Resource([
            'title' => $request->title,
            'official_id' => $official->id,
            'description' => $request->description,
            'type' => $fileMimeType,
            'file_path' => $request->file_path
        ]);

        $resource->save();

        return response()->json([
            'resource' => new ResourceResource($resource),
            'status' => true,
            'message' => 'Resource created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Resource $resource)
    {
        return response()->json([
            'resource' => new ResourceResource($resource->load('official')),
            'status' => true,
            'message' => 'Resource fetched successfully'
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Resource $resource)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Resource $resource)
    {

        $request->validate([
            'title' => 'sometimes|string',
            'description' => 'sometimes|string',
            'type' => 'sometimes|string',
            'file' => 'sometimes|mimes:jpeg,png,jpg,gif,svg,mp4,mov,avi|max:20480', // Validate image or video
        ]);

   

        $data = $request->only(['title', 'description', 'type']);
        if ($request->hasFile('file')) {
            // Delete the old file if it exists
            if ($resource->file_path) {
                Storage::disk('public')->delete($resource->file_path);
            }

            // Store the new file
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $fileName, 'public');
            $fileMimeType = $file->getMimeType(); // Get the MIME type of the file

            // Add the new file path and MIME type to the data array
            $data['file_path'] = $filePath;
            $data['type'] = $fileMimeType;
        }

        $resource->update($data);

        return response()->json([
            'resource' => new ResourceResource($resource),
            'status' => true,
            'message' => 'Resource updated successfully'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resource $resource)
    {
        $resource->delete();

        return response()->json([
            'status' => true,
            'message' => 'Resource deleted successfully'
        ], 200);
    }
}
