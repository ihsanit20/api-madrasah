<?php

namespace App\Http\Controllers;

use App\Models\FeeName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeeNameController extends Controller
{
    /**
     * Display a listing of the fee names.
     */
    public function index()
    {
        $feeNames = FeeName::with('addedBy:id,name')->get();
        return response()->json($feeNames, 200);
    }

    /**
     * Store a newly created fee name in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:fee_names,name',
            'type' => 'required|in:monthly,one_time',
        ]);

        $feeName = FeeName::create([
            'name' => $request->name,
            'type' => $request->type,
            'added_by' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Fee name created successfully.',
            'data' => $feeName
        ], 201);
    }

    /**
     * Display the specified fee name.
     */
    public function show($id)
    {
        $feeName = FeeName::with('addedBy:id,name')->findOrFail($id);
        return response()->json($feeName, 200);
    }

    /**
     * Update the specified fee name in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:fee_names,name,' . $id,
            'type' => 'required|in:monthly,one_time',
        ]);

        $feeName = FeeName::findOrFail($id);
        $feeName->update([
            'name' => $request->name,
            'type' => $request->type
        ]);

        return response()->json([
            'message' => 'Fee name updated successfully.',
            'data' => $feeName
        ], 200);
    }

    /**
     * Remove the specified fee name from storage.
     */
    public function destroy($id)
    {
        $feeName = FeeName::findOrFail($id);
        $feeName->delete();

        return response()->json([
            'message' => 'Fee name deleted successfully.'
        ], 200);
    }
}
