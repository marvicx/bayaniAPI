<?php

namespace App\Http\Controllers;

use App\Models\EmploymentDetail;
use App\Models\EmploymentDetails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
class EmploymentDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $employmentDetails = EmploymentDetails::all();
            return $this->sendSuccess($employmentDetails, 'Employment Details fetched successfully', 201);
        } catch (\Throwable $th) {
            return $this->sendError('unexpectedError', $th, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         // Log request data
        Log::info('Request Data: ', $request->all());
        $rules = [
            'employerName' => 'required',
            'personID' => 'required',
            'vessel' => 'nullable',
            'occupation' => 'required',
            'monthlySalary' => 'required',
            'agencyName' => 'nullable',
            'contractDuration' => 'required',
            'ofwType' => 'required',
            'jobSite' => 'required',
            'passport_attachment' => 'nullable',
            'coe_attachment' => 'nullable',
            'status' => 'boolean',
        ];

        $validator = Validator::make($request->all(), $rules);
        // Check if validation fails
        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }
        $emp = EmploymentDetails::create($request->only(array_keys($rules)));
        return $this->sendSuccess($emp, 'Employment created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($userId)
    {
        try {
            $details = EmploymentDetails::where('personID', $userId)->get();
            // If the person is not found, return a 404 error response
            if (!$details) {
                return $this->sendError('Employment Details not found', [], 404);
            }

            return $this->sendSuccess($details, 'Employment fetched successfully', 201);
        } catch (\Throwable $th) {
            return $this->sendError('unexpectedError', $th, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $employmentDetailId)
    {
        $employment = EmploymentDetails::find($employmentDetailId);
        if (!$employment) {
            return $this->sendError('employment not found', [], 404);
        }
        $rules = [
            'employerName' => 'required',
            'personID' => 'required',
            'vessel' => 'nullable',
            'occupation' => 'required',
            'monthlySalary' => 'required',
            'agencyName' => 'nullable',
            'contractDuration' => 'required',
            'ofwType' => 'required',
            'jobSite' => 'required',
            'passport_attachment' => 'nullable',
            'coe_attachment' => 'nullable',
            'status' => 'boolean',
        ];

        $validator = Validator::make($request->all(), $rules);
        // Check if validation fails
        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }
        $employment->update($request->only(array_keys($rules)));
        return $this->sendSuccess($employment, 'Employment created successfully', 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $employmentDetail = EmploymentDetails::findOrFail($id);
        $employmentDetail->delete();

        return response()->json(null, 204);
    }
}
