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
        try {
            $persons = Persons::all();
            return $this->sendSuccess($persons, 'persons fetched successfully', 201);
        } catch (\Throwable $th) {
            return $this->sendError('unexpectedError', $th, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    // public function store(Request $request): JsonResponse
    // {
    //     // Define validation rules
    //     $rules = [
    //         'userId' => 'required|exists:users,id',
    //         'FirstName' => 'required|string|max:255',
    //         'LastName' => 'required|string|max:255',
    //         'MiddleName' => 'nullable|string|max:255', // made nullable just in case it's optional
    //         'suffix' => 'nullable|string|max:10',
    //         'birthdate' => 'required|date',
    //         'gender' => 'required|string|max:10',
    //         'civilStatus' => 'nullable|string|max:20',
    //         'religion' => 'nullable|string|max:50',
    //         'educationalAttainment' => 'nullable|string|max:100',
    //         'course' => 'nullable|string|max:100',
    //         'addressID' => 'nullable|integer',
    //         'tags' => 'nullable|string|max:255',
    //         'passportNo' => 'required|string|max:20',
    //         'cvPath' => 'required|file|mimes:pdf,doc,docx', // Assuming file type and size limits
    //     ];

    //     // Validate the request
    //     $validator = Validator::make($request->all(), $rules);

    //     // Check if validation fails
    //     if ($validator->fails()) {
    //         return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
    //     }

    //     // Handle file upload for cvPath
    //     $file = $request->file('cvPath');
    //     $path = $file->store('cvs', 'public');

    //     // Create the Person record without userId and cvPath
    //     $personData = $request->except('userId', 'cvPath');
    //     $personData['cvPath'] = $path; // Set the correct path for CV

    //     $person = Persons::create($personData);

    //     // Update user with the personID
    //     $userId = $request->input('userId');
    //     $user = User::find($userId);

    //     if ($user) {
    //         $user->update([
    //             'personID' => $person->id, // Assuming 'person_id' is the correct field in users table
    //         ]);
    //     } else {
    //         return $this->sendError('User not found', [], 404);
    //     }

    //     // Return success response
    //     return $this->sendSuccess($person, 'Person created successfully', 201);
    // }


    /**
     * Display the specified resource.
     *
     * @param  Persons  $person
     * @return JsonResponse
     */
    public function show($userId): JsonResponse
    {
        try {

            $person = Persons::whereHas('user', function ($query) use ($userId) {
                $query->where('id', $userId);
            })->first();

            // If the person is not found, return a 404 error response
            if (!$person) {
                return $this->sendError('Person not found', [], 201);
            }
            if ($person->cvPath) {
                $person->cvPath = $this->baseUrl . '/' . $person->cvPath;
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
    // public function update(Request $request, $personId)
    // {
    //     // Find the person by ID
    //     $person = Persons::find($personId);

    //     // If the person is not found, return a 404 error response
    //     if (!$person) {
    //         return $this->sendError('Person not found', [], 404);
    //     }

    //     // Validation rules
    //     $rules = [
    //         'FirstName' => 'required|string|max:255',
    //         'LastName' => 'required|string|max:255',
    //         'MiddleName' => 'nullable|string|max:255', // Made nullable as a safeguard
    //         'suffix' => 'nullable|string|max:10',
    //         'birthdate' => 'required|date',
    //         'gender' => 'required|string|max:10',
    //         'civilStatus' => 'nullable|string|max:20',
    //         'religion' => 'nullable|string|max:50',
    //         'educationalAttainment' => 'nullable|string|max:100',
    //         'course' => 'nullable|string|max:100',
    //         'addressID' => 'nullable|integer',
    //         'employmentDetailsID' => 'nullable|string|max:50',
    //         'tags' => 'nullable|string|max:255',
    //         'passportNo' => 'required|string|max:20',  // Added length constraint to avoid DB errors
    //         'cvPath' => 'nullable|file|mimes:pdf,doc,docx|max:2048' // Only require if a file is uploaded
    //     ];

    //     // Validate the request
    //     $validator = Validator::make($request->all(), $rules);

    //     // If validation fails, return an error
    //     if ($validator->fails()) {
    //         return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
    //     }

    //     try {
    //         // Check if a new CV file is uploaded
    //         if ($request->hasFile('cvPath')) {
    //             // Handle file upload
    //             $file = $request->file('cvPath');
    //             $path = $file->store('cvs', 'public');
    //             // Merge the new path into the request data
    //             $request->merge(['cvPath' => $path]);
    //         } else {
    //             // Remove cvPath from the update if no new file is uploaded
    //             $request->request->remove('cvPath');
    //         }

    //         // Update the person record with the validated data
    //         $person->update($request->only([
    //             'FirstName',
    //             'LastName',
    //             'MiddleName',
    //             'suffix',
    //             'birthdate',
    //             'gender',
    //             'civilStatus',
    //             'religion',
    //             'educationalAttainment',
    //             'course',
    //             'addressID',
    //             'employmentDetailsID',
    //             'tags',
    //             'passportNo',
    //             'cvPath'
    //         ]));

    //         // Return a success response
    //         return $this->sendSuccess($person, 'Person updated successfully', 200);
    //     } catch (\Throwable $error) {
    //         // Catch any unexpected errors and return a 422 error
    //         return $this->sendError('Unexpected error occurred', $error->getMessage(), 422);
    //     }
    // }

    public function store(Request $request)
    {
        // Validation rules
        $commonRules = [
            'userId' => 'required|exists:users,id',
            'FirstName' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'MiddleName' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'birthdate' => 'required|date',
            'gender' => 'required|string|max:10',
            'civilStatus' => 'nullable|string|max:20',
            'religion' => 'nullable|string|max:50',
            'educationalAttainment' => 'nullable|string|max:100',
            'course' => 'nullable|string|max:100',
            'addressID' => 'nullable|integer|exists:addresses,id',
            'tags' => 'nullable|string|max:255',
            'passportNo' => 'required|string|max:20',
            'cvPath' => 'nullable|file|mimes:pdf,doc,docx', // CV file
        ];

        if ($request->id == 0 || $request->id == null || $request->id == "") {
            $rules = $commonRules;
        } else {
            $rules = array_merge(['id' => 'sometimes|exists:persons,id'], $commonRules);
        }

        // If validation fails, return an error response
        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        // If validation fails, return an error
        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        // Check if updating an existing record
        $person = Persons::find($request->id);
        if ($person) {
            $person->update($request->except('cvPath'));

            // Handle file upload if exists
            $person->update($request->all());
            if ($request->hasFile('cvPath')) {
                $cvPath = $request->file('cvPath')->store('cvs', 'public');
                $person->cvPath = $cvPath;
                $person->save();
            }

            $this->updateUser($request->userId, $person->id);
            return response()->json(['message' => 'Person updated successfully', 'person' => $person], 200);
        }

        // Otherwise, create a new record
        $person = Persons::create($request->all());
        // Handle file upload if exists
        if ($request->hasFile('cvPath')) {
            $cvPath = $request->file('cvPath')->store('cvs', 'public');
            $person->cvPath = $cvPath;
            $person->save();
        }
        $this->updateUser($request->userId, $person->id);
        return response()->json(['message' => 'Person created successfully', 'person' => $person], 201);
    }


    private function updateUser($userId, $personID)
    {
        if ($userId) {
            $user = User::find($userId);
            $user->update([
                'personID' => $personID, // Assuming 'person_id' is the correct field in users table
            ]);
        } else {
            return $this->sendError('User not found', [], 404);
        }
    }


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
