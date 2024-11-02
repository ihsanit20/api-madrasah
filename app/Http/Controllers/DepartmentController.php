<?php

namespace App\Http\Controllers;

use App\Http\Resources\DepartmentCollection;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        DepartmentCollection::wrap('departments');

        // Eager load the user relationship with only 'id' and 'name' fields
        return DepartmentCollection::make(
            Department::with(['user:id,name'])->paginate(request()->per_page)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->getValidatedDate($request);
        $validatedData['author_id'] = Auth::id(); // Set the author_id to the currently logged-in user

        $department = Department::create($validatedData);

        return response([
            "department" => DepartmentResource::make($department->load('user:id,name')),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        return response([
            "department" => DepartmentResource::make($department->load('user:id,name')), // Eager load only id and name
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $validatedData = $this->getValidatedDate($request, $department->id);
        $validatedData['author_id'] = Auth::id(); // Optionally update author_id

        $department->update($validatedData);

        return response([
            "department" => DepartmentResource::make($department->load('user:id,name')),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $department->delete();

        return response([
            "message" => "success",
        ], 200);
    }

    protected function getValidatedDate($request, $id = null)
    {
        return $request->validate([
            'name' => [
                'required',
                Rule::unique(Department::class, 'name')->ignore($id)
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'is_active' => [
                'sometimes',
                'required',
                'boolean',
            ]
        ]);
    }
}
