<?php

namespace App\Http\Controllers;

use App\Http\Resources\DepartmentClassCollection;
use App\Http\Resources\DepartmentClassResource;
use App\Models\Department;
use App\Models\DepartmentClass;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Department $department)
    {
        DepartmentClassCollection::wrap('department_classes');

        return DepartmentClassCollection::make(
            $department->department_classes()
                ->paginate(request()->per_page)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Department $department)
    {
        // return $this->getValidatedDate($request);

        $department_class = $department->department_classes()
            ->create(
                $this->getValidatedDate($department, $request)
            );

        return response([
            "department_class" => DepartmentClassResource::make($department_class),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department, $department_class_id)
    {
        $department_class = $department->department_classes()
            ->where('id', $department_class_id)
            ->first();

        // return $department_class;

        return response([
            "department_class" => DepartmentClassResource::make($department_class),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department, $department_class_id)
    {
        // return $this->getValidatedDate($request, $department->id);

        $department_class = $department->department_classes()
            ->where('id', $department_class_id)
            ->first();

        if($department_class) {
            $department_class->update($this->getValidatedDate($department, $request, $department_class->id));
        }

        return response([
            "department" => DepartmentClassResource::make($department_class),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department, $department_class_id)
    {
        $department_class = $department->department_classes()
            ->where('id', $department_class_id)
            ->first();

        if($department_class) {
            $department_class->delete();
        }

        return response([
            "message" => "success",
        ], 200);
    }

    protected function getValidatedDate($department, $request, $id = null)
    {
        return $request->validate([
            'name' => [
                'required',
                Rule::unique(DepartmentClass::class, 'name')
                    ->where("department_id", $department->id)
                    ->ignore($id)
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
