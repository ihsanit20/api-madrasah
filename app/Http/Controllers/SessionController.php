<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function index()
    {
        $sessions = Session::with(['section:id,name', 'addedBy:id,name'])->get();
        return response()->json($sessions, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'name' => 'required|string|unique:sessions,name,NULL,id,section_id,' . $request->section_id,
            'starting_date' => 'required|date',
            'ending_date' => 'required|date|after_or_equal:starting_date',
            'is_active' => 'boolean',
        ]);

        $session = Session::create([
            'section_id' => $request->section_id,
            'name' => $request->name,
            'starting_date' => $request->starting_date,
            'ending_date' => $request->ending_date,
            'is_active' => $request->is_active ?? true,
            'added_by' => Auth::id(),
        ]);

        return response()->json($session, Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $session = Session::with(['section:id,name', 'addedBy:id,name'])->findOrFail($id);
        return response()->json($session, Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'name' => 'required|string|unique:sessions,name,' . $id . ',id,section_id,' . $request->section_id,
            'starting_date' => 'required|date',
            'ending_date' => 'required|date|after_or_equal:starting_date',
            'is_active' => 'boolean',
        ]);

        $session = Session::findOrFail($id);
        $session->update([
            'section_id' => $request->section_id,
            'name' => $request->name,
            'starting_date' => $request->starting_date,
            'ending_date' => $request->ending_date,
            'is_active' => $request->is_active,
        ]);

        return response()->json($session, Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $session = Session::findOrFail($id);
        $session->delete();
        return response()->json(['message' => 'Session deleted successfully'], Response::HTTP_OK);
    }
}
