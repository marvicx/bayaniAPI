<?php

namespace App\Http\Controllers;

use App\Http\Requests\api\InformationPostRequest;
use App\Models\EventImage;
use App\Models\InformationPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InformationPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     try {
    //         $baseUrl = url('/storage/');
    //         $pageSize = $request->input('pageSize', 10);
    //         $lastPostId = $request->input('lastPostId', 1);

    //         $posts = InformationPost::with('images')
    //             ->where('id', '>', $lastPostId)
    //             ->orderBy('id')
    //             ->take($pageSize)
    //             ->get();

    //         $posts->transform(function ($post) use ($baseUrl) {
    //             // Loop through each image in the post
    //             if ($post->images) {
    //                 $post->images = $post->images->map(function ($image) use ($baseUrl) {
    //                     $image->path = $baseUrl . '/' . $image->path; // Attach base URL to the image path
    //                     return $image;
    //                 });
    //             }
    //             return $post;
    //         });
    //         return response()->json([
    //             'data' => $posts,
    //             'message' => 'Success'
    //         ]);
    //     } catch (\Throwable $th) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Unexpected error',
    //             'error' => $th->getMessage(),
    //         ], 500);
    //     }
    // }

    public function index(Request $request)
    {
        $baseUrl = url('/storage/');
        $pageSize = $request->input('pageSize', 10);
        $page = $request->input('page', 1);
        $isPublished = $request->input('is_published');
        $category = $request->input('category');

        $posts = InformationPost::with('images')
            ->when($isPublished, function ($query) use ($isPublished) {
                return $query->where('is_published', $isPublished);
            })
            ->when($category, function ($query) use ($category) {
                return $query->where('category', $category);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($pageSize, ['*'], 'page', $page);
        $posts->transform(function ($post) use ($baseUrl) {
            // Loop through each image in the post
            if ($post->images) {
                $post->images = $post->images->map(function ($image) use ($baseUrl) {
                    $image->path = $baseUrl . '/' . $image->path; // Attach base URL to the image path
                    return $image;
                });
            }
            return $post;
        });
        return response()->json($posts);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $rules = [
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'author' => 'required|string|max:100',
                'category' => 'nullable|string|max:100',
                'tags' => 'nullable|array',
                'published_date' => 'nullable|date',
                'is_published' => 'required|boolean',
                // 'views' => 'nullable',
                'attachments' => 'nullable',
                'userID' => 'required|integer',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
            }
            $person = InformationPost::create($request->only(array_keys($rules)));
            $this->storeImage($request);
            // Return a success response
            return response()->json([
                'message' => 'Information post created successfully',
                'data' => $person,
            ], 201);
        } catch (\Throwable $th) {
            return $this->sendError('unexpectedError', $th, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $postId)
    {
        try {
            $post = InformationPost::find($postId);
            if (!$post) {
                return $this->sendError('Post not found', [], 404);
            }

            $images = EventImage::where('postID', $post->id)->get();
            // Get the base URL of the application
            $baseUrl = url('/storage/');

            // Prepend the base URL to the image paths
            foreach ($images as $image) {
                $image->path = $baseUrl . '/' . $image->path;
            }
            $post['images'] = $images;
            // If the post is not found, return a 404 error response

            return $this->sendSuccess($post, 'Post fetched successfully', 201);
        } catch (\Throwable $th) {
            return $this->sendError('unexpectedError', $th, 500);
        }
    }

    public function getPostsByUser(string $userId)
    {
        try {
            // Fetch all posts by the given user ID
            $posts = InformationPost::where('userID', $userId)->get();

            // If no posts are found, return a 404 error response
            if ($posts->isEmpty()) {
                return $this->sendError('No posts found for this user', [], 201);
            }

            return $this->sendSuccess($posts, 'Posts fetched successfully', 200);
        } catch (\Throwable $th) {
            return $this->sendError('Unexpected error occurred', $th->getMessage(), 500);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $postId)
    {
        try {
            $post = InformationPost::find($postId);
            if (!$post) {
                return $this->sendError('Post not found', [], 404);
            }
            $rules = [
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'author' => 'required|string|max:100',
                'category' => 'nullable|string|max:100',
                'tags' => 'nullable|array',
                'published_date' => 'nullable|date',
                'is_published' => 'required|boolean',
                'attachments' => 'nullable',
                'userID' => 'required|integer',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
            }
            // Update the post record
            $post->update($request->only(array_keys($rules)));

            $images = EventImage::where('postID', $post->id)->get();
            // Get the base URL of the application
            $baseUrl = url('/storage/');

            // Prepend the base URL to the image paths
            foreach ($images as $image) {
                $image->path = $baseUrl . '/' . $image->path;
            }
            $post['images'] = $images;
            // If the post is not found, return a 404 error response

            return $this->sendSuccess($post, 'Post updated successfully', 200);
        } catch (\Throwable $error) {
            return $this->sendError('unexpectedError', $error, 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $postId)
    {
        try {
            $Post = InformationPost::find($postId);
            if (!$Post) {
                return $this->sendError('Post not found', [], 404);
            }
            $Post->delete();
            return response()->json(['message' => 'Post deleted successfully']);
        } catch (\Throwable $error) {
            return $this->sendError('unexpectedError', $error, 422);
        }
    }

    private function storeImage($data)
    {
        try {
            $rules = [
                'userID' => 'required|integer|exists:users,id',
                'postID' => 'required|integer|exists:information_posts,id',
                'images' => 'required|array',
                'images.*' => 'required|image',  // Validate each file in the array
            ];

            // Validate the request
            $validator = Validator::make($data->all(), $rules);
            // Check if validation fails
            if ($validator->fails()) {
                return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
            }

            $eventImages = [];
            foreach ($data->file('images') as $file) {
                // Store each image and get the file path
                $path = $file->store('event_images', 'public');

                // Create a record for each image
                $eventImage = EventImage::create([
                    'userID' => $data->userID,
                    'postID' => $data->postID,
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
}
