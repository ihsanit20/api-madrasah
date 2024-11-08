<?php

namespace App\Http\Controllers;

use App\Models\FeeName;
use Illuminate\Http\Request;

class FeeNameController extends Controller
{
    /**
     * Display a listing of the fee names.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $feeNames = FeeName::with(['addedBy:id,name'])->get();
        return response()->json([
            'message' => 'Fee names list fetched successfully',
            'feeNames' => $feeNames
        ], 200);
    }

    /**
     * Store a newly created fee name in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:fee_names',
            'type' => 'required|in:monthly,one_time',
        ]);

        $validatedData['added_by'] = auth()->id();

        $feeName = FeeName::create($validatedData);
        return response()->json([
            'message' => 'Fee name created successfully',
            'data' => $feeName
        ], 201);
    }

    /**
     * Display the specified fee name.
     *
     * @param  \App\Models\FeeName  $feeName
     * @return \Illuminate\Http\Response
     */
    public function show(FeeName $feeName)
    {
        return response()->json([
            'message' => 'Fee name fetched successfully',
            'data' => $feeName
        ], 200);
    }

    /**
     * Update the specified fee name in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FeeName  $feeName
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FeeName $feeName)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:fee_names,name,' . $feeName->id,
            'type' => 'required|in:monthly,one_time',
        ]);

        $feeName->update($validatedData);
        return response()->json([
            'message' => 'Fee name updated successfully',
            'data' => $feeName
        ], 200);
    }

    /**
     * Remove the specified fee name from storage.
     *
     * @param  \App\Models\FeeName  $feeName
     * @return \Illuminate\Http\Response
     */
    public function destroy(FeeName $feeName)
    {
        $feeName->delete();
        return response()->json([
            'message' => 'Fee name deleted successfully'
        ], 200);
    }
}
