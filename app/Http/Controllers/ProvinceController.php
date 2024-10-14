<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProvinceController extends Controller
{
    public function index(Request $request)
    {
        $query = Province::query();
    
        // Check if regionCode is present in the request
        if ($request->has('regionCode')) {
            $query->where('regionCode', $request->regionCode);
        }
    
        // Get the provinces, ordered by name
        $provinces = $query->orderBy('name', 'asc')->get();
    
        return response()->json($provinces, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|unique:provinces',
            'name' => 'required|string',
            'regionCode' => 'required|string',
            'islandGroupCode' => 'nullable|string',
            'psgc10DigitCode' => 'required|string|unique:provinces',
        ]);

        $province = Province::create($validatedData);
        return response()->json($province, Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $province = Province::find($id);

        if (!$province) {
            return response()->json(['message' => 'Province not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($province, Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $province = Province::find($id);

        if (!$province) {
            return response()->json(['message' => 'Province not found'], Response::HTTP_NOT_FOUND);
        }

        $validatedData = $request->validate([
            'code' => 'required|string|unique:provinces,code,' . $province->id,
            'name' => 'required|string',
            'regionCode' => 'required|string',
            'islandGroupCode' => 'nullable|string',
            'psgc10DigitCode' => 'required|string|unique:provinces,psgc10DigitCode,' . $province->id,
        ]);

        $province->update($validatedData);
        return response()->json($province, Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $province = Province::find($id);

        if (!$province) {
            return response()->json(['message' => 'Province not found'], Response::HTTP_NOT_FOUND);
        }

        $province->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
