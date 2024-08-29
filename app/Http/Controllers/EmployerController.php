<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Retrieve all employers
            $employers = Employer::all();
            // Return a JSON response or view
            return $this->sendSuccess($employers, 'Employer fetched successfully', 200);
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

            // Validate the request data
            $rules = [
                'userId' => 'required',
                'companyName' => 'required|string|max:255',
                'companyType' => 'required|string|max:255',
                'same_as' => 'nullable|url', //URL of the employer's website
                'logo' => 'nullable|url', // URL of the employer's logo
                'industry' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'mission' => 'nullable|string',
                'vision' => 'nullable|string',
                'addressID' => 'nullable|integer',
            ];

            // Validate the request
            $validator = Validator::make($request->all(), $rules);
            // Check if validation fails
            if ($validator->fails()) {
                return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
            }
            $employer = Employer::create($request->only(array_keys($rules)));
            // Return a JSON response or view
            $userId = $request->input('userId');
            $user = User::find($userId);
            if ($user) {
                $user->update([
                    'employerID' => $employer->id, // Assuming 'person_id' is a column in the users table
                ]);
            }
            return $this->sendSuccess($employer, 'Employer posted successfully', 201);
        } catch (\Throwable $th) {
            return $this->sendError('unexpectedError', $th, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($userId)
    {

        try {
            $employer = Employer::whereHas('user', function ($query) use ($userId) {
                $query->where('id', $userId);
            })->first();
            // If the person is not found, return a 404 error response
            if (!$employer) {
                return $this->sendError('Employer not found', [], 404);
            }

            // Return a JSON response or view 
            return $this->sendSuccess($employer, 'Employer fetched successfully', 201);
        } catch (\Throwable $th) {
            return $this->sendError('unexpectedError', $th, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $employerId)
    {

        try {
            $employer = Employer::find($employerId);
            // If the employer is not found, return a 404 error response
            if (!$employer) {
                return $this->sendError('Employer not found', [], 404);
            }
            // Validate the request data
            $rules = [
                'companyName' => 'required|string|max:255',
                'companyType' => 'required|string|max:255',
                'same_as' => 'nullable|url', //URL of the employer's website
                'logo' => 'nullable|url', // URL of the employer's logo
                'industry' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'mission' => 'nullable|string',
                'vision' => 'nullable|string',
                'addressID' => 'nullable|integer',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
            }
            // Update the employer record
            $employer->update($request->only(array_keys($rules)));
            return $this->sendSuccess($employer, 'Employer updated successfully', 200);
        } catch (\Throwable $th) {
            return $this->sendError('unexpectedError', $th, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($employerId)
    {
        try {
            $employer = Employer::find($employerId);
            if (!$employer) {
                return $this->sendError('Employer not found', [], 404);
            }
            $employer->delete();
            return response()->json(['message' => 'Employer deleted successfully']);
        } catch (\Throwable $error) {
            return $this->sendError('unexpectedError', $error, 422);
        }
    }
}