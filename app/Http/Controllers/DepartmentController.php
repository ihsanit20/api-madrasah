<?php

namespace App\Http\Controllers;

use App\Http\Resources\DepartmentCollection;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        DepartmentCollection::wrap('departments');

        return DepartmentCollection::make(Department::paginate(request()->per_page));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $this->getValidatedDate($request);

        $department = Department::create($this->getValidatedDate($request));

        return response([
            "department" => DepartmentResource::make($department),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        return response([
            "department" => DepartmentResource::make($department),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        // return $this->getValidatedDate($request, $department->id);

        $department->update($this->getValidatedDate($request, $department->id));

        return response([
            "department" => DepartmentResource::make($department),
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
