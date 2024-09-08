<?php

namespace App\Http\Controllers;

use App\Models\JobApplicants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobApplicantController extends Controller
{
    public function index()
    {
        try {
            $applicants = JobApplicants::with(['user' => function ($query) {
                $query->select('id', 'name', 'email', 'personID')
                    ->with(['person' => function ($personQuery) {
                        // Select necessary fields from 'persons' and eager load 'address'
                        $personQuery->select('id', 'FirstName', 'LastName', 'birthdate', 'addressID')
                            ->with(['address' => function ($addressQuery) {
                                // Select fields from 'address' table
                                $addressQuery->select(
                                    'id',
                                    'provinceID',
                                    'cityID',
                                    'barangayID',
                                    'street',
                                    'zipcode'
                                );
                            }]);
                    }]);
            }])->get()->map(function ($applicant) {
                return [
                    'jobID' => $applicant->jobID,
                    'appliedUserID' => $applicant->appliedUserID,
                    'status' => $applicant->status,
                    'appliedDate' => $applicant->appliedDate,
                    'created_at' => $applicant->created_at,
                    'updated_at' => $applicant->updated_at,
                    'name' => $applicant->user->name,
                    'email' => $applicant->user->email,
                    'firstName' => $applicant->user->person->FirstName ?? null,
                    'lastName' => $applicant->user->person->LastName ?? null,
                    'birthdate' => $applicant->user->person->birthdate ?? null,
                    'address' => [
                        'provinceID' => $applicant->user->person->address->provinceID ?? null,
                        'cityID' => $applicant->user->person->address->cityID ?? null,
                        'barangayID' => $applicant->user->person->address->barangayID ?? null,
                        'street' => $applicant->user->person->address->street ?? null,
                        'zipcode' => $applicant->user->person->address->zipcode ?? null,
                    ],
                ];
            });
            // $applicants = JobApplicants::all();
            return $this->sendSuccess($applicants, 'Jobs applicants fetched successfully', 200);
        } catch (\Throwable $th) {
            return $this->sendError('unexpectedError', $th, 500);
        }
    }
    public function store(Request $request)
    {
        try {
            $rules = [
                'jobID' => 'required|exists:job_posts,id',
                'appliedUserID' => 'required|exists:users,id',
                'status' => 'required',
                'id' => 'sometimes|exists:job_applicants,id', // Add this rule to validate the optional ID
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
            }

            $data = $request->only(array_keys($rules));
            $id = $request->input('id');

            if ($id) {
                // If an ID is present, update the existing JobApplicant
                $jobApplicant = JobApplicants::findOrFail($id);
                $jobApplicant->update($data);
                $message = 'Job applicant updated successfully';
            } else {
                // Otherwise, create a new JobApplicant
                $jobApplicant = JobApplicants::create($data);
                $message = 'Job applicant created successfully';
            }

            return response()->json([
                'message' => $message,
                'data' => $jobApplicant,
            ], 201);
        } catch (\Throwable $th) {
            return $this->sendError('Unexpected Error', $th->getMessage(), 500);
        }
    }
}
