<?php

namespace App\Http\Controllers;

use App\Models\EmploymentDetail;
use App\Models\EmploymentDetails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        $rules = [
            'employerName' => 'required',
            'employerAddressID' => 'required',
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
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $employmentDetail = EmploymentDetails::findOrFail($id);
        return response()->json($employmentDetail);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $employmentDetail = EmploymentDetails::findOrFail($id);

        $validatedData = $request->validate([
            'employerName' => 'required|string|max:255',
            'employerAddressID' => 'required|string|max:255',
            'vessel' => 'nullable|string|max:255',
            'occupation' => 'required|string|max:255',
            'monthlySalary' => 'required|string|max:255',
            'agencyName' => 'nullable|string|max:255',
            'contractDuration' => 'required|string|max:255',
            'ofwType' => 'required|string|max:255',
            'jobSite' => 'required|string|max:255',
            'passport_attachment' => 'nullable|string|max:255',
            'coe_attachment' => 'nullable|string|max:255',
            'status' => 'boolean',
        ]);

        $employmentDetail->update($validatedData);

        return response()->json($employmentDetail);
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