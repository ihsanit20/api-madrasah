<?php

namespace App\Http\Controllers;

use App\Models\Zamat;
use Illuminate\Http\Request;

class ZamatController extends Controller
{
    /**
     * Display a listing of the zamats.
     */
    public function index()
    {
        $zamats = Zamat::with(['section', 'added_by:id,name'])->get();
        return response()->json([
            'message' => 'Zamats list fetched successfully',
            'zamats' => $zamats
        ], 200);
    }

    /**
     * Store a newly created zamat in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'section_id' => 'nullable|exists:sections,id',
            'added_by' => 'nullable|exists:users,id',
            'name' => 'required|string|unique:zamats,name',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'priority' => 'integer|min:0|max:255',
        ]);

        $zamat = Zamat::create($validatedData);
        return response()->json($zamat, 201);
    }

    /**
     * Display the specified zamat.
     */
    public function show(Zamat $zamat)
    {
        return response()->json($zamat);
    }

    /**
     * Update the specified zamat in storage.
     */
    public function update(Request $request, Zamat $zamat)
    {
        $validatedData = $request->validate([
            'section_id' => 'nullable|exists:sections,id',
            'added_by' => 'nullable|exists:users,id',
            'name' => 'required|string|unique:zamats,name,' . $zamat->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'priority' => 'integer|min:0|max:255',
        ]);

        $zamat->update($validatedData);
        return response()->json($zamat);
    }

    /**
     * Remove the specified zamat from storage.
     */
    public function destroy(Zamat $zamat)
    {
        $zamat->delete();
        return response()->json(['message' => 'Zamat deleted successfully']);
    }
}
