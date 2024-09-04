<?php

namespace App\Http\Controllers;

use App\Models\EventImage;
use Illuminate\Http\Request;

class EventImageController extends Controller
{
    /**
     * Display a listing of the event images.
     */
    public function index()
    {
        $eventImages = EventImage::all();
        return response()->json($eventImages);
    }

    /**
     * Store a newly created event image in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'userID' => 'required|integer|exists:users,id',
            'postID' => 'required|integer|exists:posts,id',
            'image' => 'required|image|max:2048',
        ]);

        $path = $request->file('image')->store('event_images', 'public');

        $eventImage = EventImage::create([
            'userID' => $request->userID,
            'postID' => $request->postID,
            'image' => $request->file('image')->getClientOriginalName(),
            'path' => $path,
        ]);

        return response()->json($eventImage, 201);
    }

    /**
     * Display the specified event image.
     */
    public function show($id)
    {
        $eventImage = EventImage::findOrFail($id);
        return response()->json($eventImage);
    }

    /**
     * Update the specified event image in storage.
     */
    public function update(Request $request, $id)
    {
        $eventImage = EventImage::findOrFail($id);

        $request->validate([
            'userID' => 'required|integer|exists:users,id',
            'postID' => 'required|integer|exists:posts,id',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($eventImage->path) {
                Storage::disk('public')->delete($eventImage->path);
            }

            // Store new image
            $path = $request->file('image')->store('event_images', 'public');
            $eventImage->update([
                'image' => $request->file('image')->getClientOriginalName(),
                'path' => $path,
            ]);
        }

        $eventImage->update([
            'userID' => $request->userID,
            'postID' => $request->postID,
        ]);

        return response()->json($eventImage);
    }

    /**
     * Remove the specified event image from storage.
     */
    public function destroy($id)
    {
        $eventImage = EventImage::findOrFail($id);

        if ($eventImage->path) {
            Storage::disk('public')->delete($eventImage->path);
        }

        $eventImage->delete();

        return response()->json(null, 204);
    }
}
