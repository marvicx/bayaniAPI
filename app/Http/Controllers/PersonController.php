<?php

namespace App\Http\Controllers;

use App\Models\Persons;
use App\Models\User;
use Faker\Provider\ar_EG\Person;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $persons = Persons::all();
        return response()->json($persons);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        //return $this->sendSuccess('User retrieved successfully');
        $rules = [
            'userId' => 'required',
            'FirstName' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'MiddleName' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'birthdate' => 'required|date',
            'gender' => 'required|string|max:10',
            'civilStatus' => 'nullable|string|max:20',
            'religion' => 'nullable|string|max:50',
            'educationalAttainment' => 'nullable|string|max:100',
            'course' => 'nullable|string|max:100',
            'addressID' => 'nullable|integer',
            'employmentDetailsID' => 'nullable|string|max:50',
            'tags' => 'nullable|string|max:255',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);
        // Check if validation fails
        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        $person = Persons::create($request->only(array_keys($rules)));
        $userId = $request->input('userId');
        $user = User::find($userId);
        if ($user) {
            $user->update([
                'personID' => $person->id, // Assuming 'person_id' is a column in the users table
            ]);
        }
        // Return success response
        return $this->sendSuccess($person, 'Person created successfully', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Persons  $person
     * @return JsonResponse
     */
    public function show($personId): JsonResponse
    {
        try {
            $person = Persons::find($personId);
            // If the person is not found, return a 404 error response
            if (!$person) {
                return $this->sendError('Person not found', [], 404);
            }
            return $this->sendSuccess($person, 'Person fetched successfully', 201);
        } catch (\Throwable $th) {
            return $this->sendError('unexpectedError', $th, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Persons  $person
     * @return JsonResponse
     */
    public function update(Request $request, $personId): JsonResponse
    {
        try {
            $person = Persons::find($personId);
            // If the person is not found, return a 404 error response
            if (!$person) {
                return $this->sendError('Person not found', [], 404);
            }
            $rules = [
                'FirstName' => 'required|string|max:255',
                'LastName' => 'required|string|max:255',
                'MiddleName' => 'required|string|max:255',
                'suffix' => 'nullable|string|max:10',
                'birthdate' => 'required|date',
                'gender' => 'required|string|max:10',
                'civilStatus' => 'nullable|string|max:20',
                'religion' => 'nullable|string|max:50',
                'educationalAttainment' => 'nullable|string|max:100',
                'course' => 'nullable|string|max:100',
                'addressID' => 'nullable|integer',
                'employmentDetailsID' => 'nullable|string|max:50',
                'tags' => 'nullable|string|max:255',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
            }
            // Update the person record
            $person->update($request->only(array_keys($rules)));
            return $this->sendSuccess($person, 'Person updated successfully', 200);
        } catch (\Throwable $error) {
            return $this->sendError('unexpectedError', $error, 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Persons  $person
     * @return JsonResponse
     */
    public function destroy($personId): JsonResponse
    {
        try {
            $person = Persons::find($personId);
            if (!$person) {
                return $this->sendError('Person not found', [], 404);
            }
            $person->delete();
            return response()->json(['message' => 'Person deleted successfully']);
        } catch (\Throwable $error) {
            return $this->sendError('unexpectedError', $error, 422);
        }
    }
}
