<?php

namespace App\Http\Controllers;

use App\Models\Municipality;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MunicipalityController extends Controller
{
    public function index(Request $request)
    {
        $query = Municipality::query();
    
        // Check if provinceCode is present in the request
        if ($request->has('provinceCode')) {
            $query->where('provinceCode', $request->provinceCode);
        }
    
        // Get the municipalities
        $municipalities = $query->get();
    
        return response()->json($municipalities, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|unique:municipalities',
            'name' => 'required|string',
            'oldName' => 'nullable|string',
            'isCapital' => 'boolean',
            'isCity' => 'boolean',
            'isMunicipality' => 'boolean',
            'districtCode' => 'nullable|string',
            'provinceCode' => 'nullable|string',
            'regionCode' => 'nullable|string',
            'islandGroupCode' => 'nullable|string',
            'psgc10DigitCode' => 'nullable|string',
        ]);

        $municipality = Municipality::create($validatedData);
        return response()->json($municipality, Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $municipality = Municipality::find($id);

        if (!$municipality) {
            return response()->json(['message' => 'Municipality not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($municipality, Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $municipality = Municipality::find($id);

        if (!$municipality) {
            return response()->json(['message' => 'Municipality not found'], Response::HTTP_NOT_FOUND);
        }

        $validatedData = $request->validate([
            'code' => 'required|string|unique:municipalities,code,' . $municipality->id,
            'name' => 'required|string',
            'oldName' => 'nullable|string',
            'isCapital' => 'boolean',
            'isCity' => 'boolean',
            'isMunicipality' => 'boolean',
            'districtCode' => 'nullable|string',
            'provinceCode' => 'nullable|string',
            'regionCode' => 'nullable|string',
            'islandGroupCode' => 'nullable|string',
            'psgc10DigitCode' => 'nullable|string',
        ]);

        $municipality->update($validatedData);
        return response()->json($municipality, Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $municipality = Municipality::find($id);

        if (!$municipality) {
            return response()->json(['message' => 'Municipality not found'], Response::HTTP_NOT_FOUND);
        }

        $municipality->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
