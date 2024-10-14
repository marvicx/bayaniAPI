<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BarangayController extends Controller
{
    public function index(Request $request)
    {
        $query = Barangay::query();
    
        // Check if cityCode is present and not empty
        if ($request->has('cityCode') && !empty($request->cityCode)) {
            $query->where('cityCode', $request->cityCode);
        }
    
        // Check if municipalityCode is present and not empty
        if ($request->has('municipalityCode') && !empty($request->municipalityCode)) {
            $query->where('municipalityCode', $request->municipalityCode);
        }
    
        // Get the barangays
        $barangays = $query->get();
    
        return response()->json($barangays, Response::HTTP_OK);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|unique:barangays',
            'name' => 'required|string',
            'oldName' => 'nullable|string',
            'subMunicipalityCode' => 'nullable|string',
            'cityCode' => 'nullable|string',
            'municipalityCode' => 'nullable|string',
            'districtCode' => 'nullable|string',
            'provinceCode' => 'nullable|string',
            'regionCode' => 'nullable|string',
            'islandGroupCode' => 'nullable|string',
            'psgc10DigitCode' => 'nullable|string',
        ]);

        $barangay = Barangay::create($validatedData);
        return response()->json($barangay, Response::HTTP_CREATED);
    }

    public function show($code)
    {
        $barangay = Barangay::find($code);

        if (!$barangay) {
            return response()->json(['message' => 'Barangay not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($barangay, Response::HTTP_OK);
    }

    public function update(Request $request, $code)
    {
        $barangay = Barangay::find($code);

        if (!$barangay) {
            return response()->json(['message' => 'Barangay not found'], Response::HTTP_NOT_FOUND);
        }

        $validatedData = $request->validate([
            'code' => 'required|string|unique:barangays,code,' . $barangay->code,
            'name' => 'required|string',
            'oldName' => 'nullable|string',
            'subMunicipalityCode' => 'nullable|string',
            'cityCode' => 'nullable|string',
            'municipalityCode' => 'nullable|string',
            'districtCode' => 'nullable|string',
            'provinceCode' => 'nullable|string',
            'regionCode' => 'nullable|string',
            'islandGroupCode' => 'nullable|string',
            'psgc10DigitCode' => 'nullable|string',
        ]);

        $barangay->update($validatedData);
        return response()->json($barangay, Response::HTTP_OK);
    }

    public function destroy($code)
    {
        $barangay = Barangay::find($code);

        if (!$barangay) {
            return response()->json(['message' => 'Barangay not found'], Response::HTTP_NOT_FOUND);
        }

        $barangay->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
