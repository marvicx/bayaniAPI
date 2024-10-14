<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Persons;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $addresses = Address::all();
        return response()->json($addresses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $rules = [
                'userId' => 'required',
                'provinceID' => 'required',
                'regionID' => 'required',
                'cityID' => 'required',
                'barangayID' => 'required',
                'zipcode' => 'required',
                'street' => 'required|string',
                'mobileNo' => 'required',
                'email' => 'required|email',
                'telephoneNo' => 'nullable|string',
                'fax' => 'nullable|string',
                'ofwForeignAddress' => 'nullable|string',
                'ofwCountry' => 'nullable|string',
                'ofwContactNo' => 'nullable|string',
            ];
            // Validate the request
            $validator = Validator::make($request->all(), $rules);
            // Check if validation fails
            if ($validator->fails()) {
                return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
            }

            $address = Address::create($request->only(array_keys($rules)));

            $userId = $request->input('userId');
            $user = Persons::whereHas('user', function ($query) use ($userId) {
                $query->where('id', $userId);
            })->first();
            if ($user) {
                $user->update([
                    'addressID' => $address->id,
                ]);
            }
            // Return success response
            return $this->sendSuccess($address, 'address created successfully', 201);
        } catch (\Throwable $th) {
            return $this->sendError('unexpectedError', $th, 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($userId)
    {
        $person = Persons::whereHas('user', function ($query) use ($userId) {
            $query->where('id', $userId);
        })->first();

        if (!$person) {
            return $this->sendError('userId not found', [], 404);
        }

        $address = Address::findOrFail($person->addressID);

        if (!$address) {
            return $this->sendError('address not found', [], 404);
        }

        return $this->sendSuccess($address, 'address fetched successfully', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $addressID)
    {
        try {
            $address = Address::find($addressID);
            // If the address is not found, return a 404 error response
            if (!$address) {
                return $this->sendError('address not found', [], 404);
            }
            $rules = [
                'userId' => 'required',
                'provinceID' => 'required',
                'regionID' => 'required',
                'cityID' => 'required',
                'barangayID' => 'required',
                'zipcode' => 'required',
                'street' => 'required|string',
                'mobileNo' => 'required', 
                'telephoneNo' => 'nullable|string',
                'fax' => 'nullable|string',
                'ofwForeignAddress' => 'nullable|string',
                'ofwCountry' => 'nullable|string',
                'ofwContactNo' => 'nullable|string',
            ];
            // Validate the request
            $validator = Validator::make($request->all(), $rules);
            // Check if validation fails
            if ($validator->fails()) {
                return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
            }

            $address->update($request->only(array_keys($rules)));
            // Return success response
            return $this->sendSuccess($address, 'address created successfully', 201);
        } catch (\Throwable $th) {
            return $this->sendError('unexpectedError', $th, 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $address = Address::findOrFail($id);
        $address->delete();

        return response()->json(['message' => 'Address deleted successfully']);
    }
}