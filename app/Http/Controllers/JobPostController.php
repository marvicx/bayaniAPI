<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use App\Models\Persons;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getAllJobs(Request $request)
    {
        try {

            // Fetch all job posts
            $jobPosts = JobPost::all();
            if ($request->userId == 0 || $request->userId == null) {
                return $this->sendSuccess($jobPosts, 'persons fetched successfully', 201);
            }


            // Fetch the person by user ID
            $person = Persons::whereHas('user', function ($query) use ($request) {
                $query->where('id', $request->userId);
            })->first();

            // If the person is not found, return an error
            if (!$person) {
                return $this->sendError('Person not found', [], 404);
            }

            // Get the person's tags and convert them into an array
            $personTags = explode(',', $person->tags);




            // Filter and sort job posts based on tag matches
            $sortedJobPosts = $jobPosts->sortByDesc(function ($jobPost) use ($personTags) {
                // Get the job post tags and convert them into an array
                $jobTags = explode(',', $jobPost->tags);

                // Calculate the number of matching tags between the person and job post
                $matchingTags = array_intersect($personTags, $jobTags);

                // Sort by the number of matching tags (higher number means higher priority)
                return count($matchingTags);
            });

            return $this->sendSuccess($sortedJobPosts->values()->all(), 'Jobs fetched successfully', 200);
        } catch (\Throwable $th) {
            return $this->sendError('unexpectedError', $th, 500);
        }
    }


    public function fetchJobBySearchKey(Request $request)
    {
        try {
            // Get the search keyword from the request
            $search = $request->input('search');

            // Query to search for jobs where the title matches %search%
            $jobPosts = JobPost::when($search, function ($query, $search) {
                $query->where('title', 'LIKE', '%' . $search . '%');
            })->get();

            return $this->sendSuccess($jobPosts, 'Jobs fetched successfully', 200);
        } catch (\Throwable $th) {
            return $this->sendError('unexpectedError', $th, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'postedby' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date_posted' => 'required|date',
            'valid_through' => 'nullable|date',
            'employment_type' => 'required|string|max:255',
            'hiring_organization_name' => 'required|string|max:255',
            'hiring_organization_same_as' => 'nullable|string|max:255',
            'hiring_organization_logo' => 'nullable|string|max:255',
            'job_location_street_address' => 'required|string|max:255',
            'job_location_address_locality' => 'required|string|max:255',
            'job_location_address_region' => 'required|string|max:255',
            'job_location_postal_code' => 'required|string|max:255',
            'job_location_address_country' => 'required|string|max:255',
            'base_salary_value' => 'required|string',
            'base_salary_currency' => 'required|string|max:3',
            'base_salary_unit_text' => 'string|max:255',
            'job_benefits' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'qualifications' => 'nullable|string',
            'skills' => 'nullable|string',
            'industry' => 'nullable|string|max:255',
            'applicant_location_requirements' => 'nullable|string|max:255',
            'job_location_type' => 'nullable|string|max:255',
            'work_hours' => 'nullable|string|max:255',
            'tags' => 'nullable|string|max:255',
            'comments' => 'nullable|string',
            'status' => 'nullable|numeric',

        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }
        $jobPost = JobPost::create($request->only(array_keys($rules)));

        // Return a success response
        return response()->json([
            'message' => 'Job post created successfully',
            'data' => $jobPost,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($jobId)
    {
        try {
            $job = JobPost::find($jobId);

            // If the job is not found, return a 404 error response
            if (!$job) {
                return $this->sendError('Job not found', [], 404);
            }

            $user = User::find($job->postedby);

            // Add the user's email to the job object
            $job->user_email = $user->email;

            return $this->sendSuccess($job, 'Job fetched successfully', 201);
        } catch (\Throwable $th) {
            return $this->sendError('unexpectedError', $th, 500);
        }
    }

    public function getJobPostsByUser(string $userId)
    {
        try {
            // Fetch all posts by the given user ID
            $posts = JobPost::where('postedby', $userId)->get();

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
    public function update(Request $request, $jobId)
    {

        try {
            $job = JobPost::find($jobId);
            if (!$job) {
                return $this->sendError('job not found', [], 404);
            }
            $rules = [
                'postedby' => 'required',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'date_posted' => 'required|date',
                'valid_through' => 'nullable|date',
                'employment_type' => 'required|string|max:255',
                'hiring_organization_name' => 'required|string|max:255',
                'hiring_organization_same_as' => 'nullable|string|max:255',
                'hiring_organization_logo' => 'nullable|string|max:255',
                'job_location_street_address' => 'required|string|max:255',
                'job_location_address_locality' => 'required|string|max:255',
                'job_location_address_region' => 'required|string|max:255',
                'job_location_postal_code' => 'required|string|max:255',
                'job_location_address_country' => 'required|string|max:255',
                'base_salary_value' => 'required|string',
                'base_salary_currency' => 'required|string|max:3',
                'base_salary_unit_text' => 'string|max:255',
                'job_benefits' => 'nullable|string',
                'responsibilities' => 'nullable|string',
                'qualifications' => 'nullable|string',
                'skills' => 'nullable|string',
                'industry' => 'nullable|string|max:255',
                'applicant_location_requirements' => 'nullable|string|max:255',
                'job_location_type' => 'nullable|string|max:255',
                'work_hours' => 'nullable|string|max:255',
                'tags' => 'nullable|string|max:255',
                'comments' => 'nullable|string',
                'status' => 'nullable|numeric',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
            }
            // Update the job record
            $job->update($request->only(array_keys($rules)));
            return $this->sendSuccess($job, 'Post updated successfully', 200);
        } catch (\Throwable $error) {
            return $this->sendError('unexpectedError', $error, 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($jobId)
    {

        try {
            $Job = JobPost::find($jobId);
            if (!$Job) {
                return $this->sendError('Job not found', [], 404);
            }
            $Job->delete();
            return response()->json(['message' => 'Job deleted successfully']);
        } catch (\Throwable $error) {
            return $this->sendError('unexpectedError', $error, 422);
        }
    }
}
