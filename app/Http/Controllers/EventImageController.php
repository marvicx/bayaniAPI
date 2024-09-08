<?php

namespace App\Http\Controllers;

use App\Models\EventImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EventImageController extends Controller
{
    /**
     * Display a listing of the event images.
     */
    public function index()
    {

        try {
            $eventImages = EventImage::all();
            return $this->sendSuccess($eventImages, 'Images fetched successfully', 200);
        } catch (\Throwable $th) {
            return $this->sendError('unexpectedError', $th, 500);
        }
    }

    /**
     * Store a newly created event image in storage.
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'userID' => 'required|integer|exists:users,id',
                'postID' => 'required|integer|exists:information_posts,id',
                'images' => 'required|array',
                'images.*' => 'required|image',  // Validate each file in the array
            ];

            // Validate the request
            $validator = Validator::make($request->all(), $rules);
            // Check if validation fails
            if ($validator->fails()) {
                return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
            }

            $eventImages = [];
            foreach ($request->file('images') as $file) {
                // Store each image and get the file path
                $path = $file->store('event_images', 'public');

                // Create a record for each image
                $eventImage = EventImage::create([
                    'userID' => $request->userID,
                    'postID' => $request->postID,
                    'image' => $file->getClientOriginalName(),
                    'path' => $path,
                ]);

                $eventImages[] = $eventImage;
            }

            return $this->sendSuccess($eventImages, 'Images saved successfully', 200);
        } catch (\Throwable $th) {
            return $this->sendError('unexpectedError', $th, 500);
        }
    }

    /**
     * Display the specified event image.
     */
    public function show($postId)
    {
        try {
            $images = EventImage::whereHas('InformationPost', function ($query) use ($postId) {
                $query->where('id', $postId);
            })->all();
            // If the person is not found, return a 404 error response
            if (!$images) {
                return response()->noContent();
            }

            // Return a JSON response or view
            return $this->sendSuccess($images, 'Images fetched successfully', 201);
        } catch (\Throwable $th) {
            return $this->sendError('unexpectedError', $th, 500);
        }
    }


    public function getImageByPostId($postId)
    {
        try {
            $images = EventImage::whereHas('InformationPost', function ($query) use ($postId) {
                $query->where('id', $postId);
            })->get();
            // If the person is not found, return a 404 error response
            if (!$images) {
                return response()->noContent();
            }

            // Return a JSON response or view
            return $this->sendSuccess($images, 'Images fetched successfully', 201);
        } catch (\Throwable $th) {
            return $this->sendError('unexpectedError', $th, 500);
        }
    }

    /**
     * Update the specified event image in storage.
     */
    public function update(Request $request, $imageID)
    {

        $eventImage = EventImage::findOrFail($imageID);
        if (!$eventImage) {
            return $this->sendError('image not found', [], 404);
        }
        return $eventImage;
        $rules = [
            'userID' => 'required|integer|exists:users,id',
            'postID' => 'required|integer|exists:information_posts,id',
            'image' => 'nullable|image|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

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
        return $this->sendSuccess($eventImage, 'Employer fetched successfully', 201);
    }

    /**
     * Remove the specified event image from storage.
     */
    public function destroy(EventImage $eventImage)
    {
        // Check if the image exists
        if (!$eventImage) {
            return $this->sendError('Image not found', 404);
        }

        // Delete the image file if it exists
        if ($eventImage->path && Storage::disk('public')->exists($eventImage->path)) {
            Storage::disk('public')->delete($eventImage->path);
        }

        // Delete the image record
        $eventImage->delete();

        return $this->sendSuccess(null, 'Image deleted successfully', 200);
    }
}
