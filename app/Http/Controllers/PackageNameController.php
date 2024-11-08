<?php

namespace App\Http\Controllers;

use App\Models\PackageName;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PackageNameController extends Controller
{
    /**
     * Display a listing of the package names.
     */
    public function index()
    {
        $packageNames = PackageName::with('addedBy:id,name')->get();

        return response()->json([
            'message' => 'Package names fetched successfully',
            'packageNames' => $packageNames
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created package name in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:package_names,name',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['added_by'] = Auth::id();

        $packageName = PackageName::create($validated);

        return response()->json([
            'message' => 'Package name created successfully',
            'data' => $packageName
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified package name.
     */
    public function show(PackageName $packageName)
    {
        return response()->json([
            'message' => 'Package name fetched successfully',
            'data' => $packageName
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified package name in storage.
     */
    public function update(Request $request, PackageName $packageName)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:package_names,name,' . $packageName->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $packageName->update($validated);

        return response()->json([
            'message' => 'Package name updated successfully',
            'data' => $packageName
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified package name from storage (soft delete).
     */
    public function destroy(PackageName $packageName)
    {
        $packageName->delete();

        return response()->json([
            'message' => 'Package name deleted successfully'
        ], Response::HTTP_OK);
    }
}
