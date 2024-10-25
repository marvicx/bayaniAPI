<?php

namespace App\Http\Controllers;

use App\Models\JobCategory;
use Illuminate\Http\Request;

class JobCategoryController extends Controller
{
    // Display a listing of job categories
    public function index()
    {
        $categories = JobCategory::all();
        return $this->sendSuccess($categories, 'job categories Details fetched successfully', 201);
    }

    // Store a newly created job category
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:job_categories,name',
        ]);

        $category = JobCategory::create([
            'name' => $request->name,
        ]);

        return response()->json($category, 201);
    }

    // Display a specific job category
    public function show($id)
    {
        $category = JobCategory::findOrFail($id);
        return response()->json($category);
    }

    // Update a job category
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:job_categories,name,' . $id,
        ]);

        $category = JobCategory::findOrFail($id);
        $category->update([
            'name' => $request->name,
        ]);

        return response()->json($category);
    }

    // Remove a job category
    public function destroy($id)
    {
        $category = JobCategory::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Job category deleted successfully']);
    }
}
