<?php

namespace App\Http\Controllers;

use App\Models\Regions;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        return Regions::all(); // Return all regions
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:10|unique:region',
            'name' => 'required|string',
            'regionName' => 'required|string',
            'islandGroupCode' => 'required|string',
            'psgc10DigitCode' => 'required|string|max:11|unique:region',
        ]);

        $region = Regions::create($request->all()); // Create a new region
        return response()->json($region, 201); // Return created region with status 201
    }

    // Display the specified resource.
    public function show($id)
    {
        $region = Regions::findOrFail($id); // Find region by ID
        return response()->json($region); // Return the region
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        $region = Regions::findOrFail($id); // Find region by ID

        $request->validate([
            'code' => 'sometimes|required|string|max:10|unique:region,code,' . $region->id,
            'name' => 'sometimes|required|string',
            'regionName' => 'sometimes|required|string',
            'islandGroupCode' => 'sometimes|required|string',
            'psgc10DigitCode' => 'sometimes|required|string|max:11|unique:region,psgc10DigitCode,' . $region->id,
        ]);

        $region->update($request->all()); // Update the region
        return response()->json($region); // Return updated region
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        $region = Regions::findOrFail($id); // Find region by ID
        $region->delete(); // Delete the region
        return response()->json(null, 204); // Return 204 No Content
    }
}
