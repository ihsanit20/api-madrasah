<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Display a listing of the sections.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sections = Section::with(['added_by:id,name'])->get();
        return response()->json([
            'message' => 'Sections list fetched successfully',
            'sections' => $sections
        ], 200);
    }

    /**
     * Store a newly created section in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:sections',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
    
        // Add the current user's ID to the validated data
        $validatedData['added_by'] = auth()->id();
    
        $section = Section::create($validatedData);
        return response()->json($section, 201);
    }

    /**
     * Display the specified section.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section)
    {
        return response()->json($section);
    }

    /**
     * Update the specified section in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Section $section)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:sections,name,' . $section->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'added_by' => 'nullable|exists:users,id',
        ]);

        $section->update($validatedData);
        return response()->json($section);
    }

    /**
     * Remove the specified section from storage.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Section $section)
    {
        $section->delete();
        return response()->json(['message' => 'Section deleted successfully']);
    }
}
