<?php

namespace App\Http\Controllers;

use App\Http\Requests\api\InformationPostRequest;
use App\Models\InformationPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InformationPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $posts = InformationPost::all();
            return $this->sendSuccess($posts, 'Posts fetched successfully', 200);
        } catch (\Throwable $th) {
            return $this->sendError('unexpectedError', $th, 500);
        }
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
                'author' => 'required',
                'category' => 'nullable|string|max:100',
                'tags' => 'nullable|array',
                'published_date' => 'nullable|date',
                'is_published' => 'boolean',
                // 'views' => 'nullable',
                'attachments' => 'nullable',
                'userID' => 'required|integer',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
            }
            $person = InformationPost::create($request->only(array_keys($rules)));
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
            $person = InformationPost::find($postId);
            // If the person is not found, return a 404 error response
            if (!$person) {
                return $this->sendError('Post not found', [], 404);
            }
            return $this->sendSuccess($person, 'Post fetched successfully', 201);
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
                return $this->sendError('No posts found for this user', [], 404);
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
}
