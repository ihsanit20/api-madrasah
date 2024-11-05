<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the subjects.
     */
    public function index()
    {
        $subjects = Subject::with(['zamat:id,name', 'addedBy:id,name'])->get();
        return response()->json(['subjects' => $subjects]);
    }

    /**
     * Store a newly created subject in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'zamat_id'      => 'nullable|exists:zamats,id',
            'name'          => 'required|string',
            'book_name'     => 'nullable|string',
            'subject_code'  => 'required|integer|unique:subjects,subject_code',
            'priority'      => 'nullable|integer|min:0',
            'is_active'     => 'nullable|boolean'
        ]);

        $validatedData['added_by'] = auth()->id();

        $subject = Subject::create($validatedData);
        return response()->json(['message' => 'Subject created successfully', 'subject' => $subject], 201);
    }

    /**
     * Display the specified subject.
     */
    public function show(Subject $subject)
    {
        $subject->load(['zamat:id,name', 'addedBy:id,name']);
        return response()->json($subject);
    }

    /**
     * Update the specified subject in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $validatedData = $request->validate([
            'zamat_id'      => 'nullable|exists:zamats,id',
            'name'          => 'required|string',
            'book_name'     => 'nullable|string',
            'subject_code'  => 'required|integer|unique:subjects,subject_code,' . $subject->id,
            'priority'      => 'nullable|integer|min:0',
            'is_active'     => 'nullable|boolean'
        ]);

        $subject->update($validatedData);
        return response()->json(['message' => 'Subject updated successfully', 'subject' => $subject]);
    }

    /**
     * Remove the specified subject from storage.
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();
        return response()->json(['message' => 'Subject deleted successfully']);
    }
}
