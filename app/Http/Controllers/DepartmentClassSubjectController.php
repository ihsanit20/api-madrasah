<?php

namespace App\Http\Controllers;

use App\Http\Resources\DepartmentClassSubjectCollection;
use App\Http\Resources\DepartmentClassSubjectResource;
use App\Models\Department;
use App\Models\DepartmentClass;
use App\Models\DepartmentClassSubject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentClassSubjectController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index($department_id, $department_class_id)
    {
        DepartmentClassSubjectCollection::wrap('department_class_subjects');

        return DepartmentClassSubjectCollection::make(
            DepartmentClassSubject::query()
                ->where("department_class_id", $department_class_id)
                ->paginate(request()->per_page)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Department $department, DepartmentClass $class)
    {
        // return $this->getValidatedDate($department->id, $class->id, $request);

        if($class->department_id != $department->id) {
            return response('Not Found!', 404);
        }

        $department_class_subject = $class->department_class_subjects()
            ->create(
                $this->getValidatedDate($department->id, $class->id, $request)
            );

        return response([
            "department_class_subject" => DepartmentClassSubjectResource::make($department_class_subject),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department, DepartmentClass $class, DepartmentClassSubject $subject)
    {
        // return $subject;

        if($class->department_id != $department->id || $subject->department_class_id != $class->id) {
            return response('Not Found!', 404);
        }

        return response([
            "department_class_subject" => DepartmentClassSubjectResource::make($subject),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department, DepartmentClass $class, DepartmentClassSubject $subject)
    {
        if($class->department_id != $department->id || $subject->department_class_id != $class->id) {
            return response('Not Found!', 404);
        }

        // return $this->getValidatedDate($department->id, $class->id, $request, $subject->id);

        $subject->update(
            $this->getValidatedDate($department->id, $class->id, $request, $subject->id)
        );

        return response([
            "department_class_subject" => DepartmentClassSubjectResource::make($subject),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department, DepartmentClass $class, DepartmentClassSubject $subject)
    {
        if($class->department_id != $department->id || $subject->department_class_id != $class->id) {
            return response('Not Found!', 404);
        }

        $subject->delete();

        return response([
            "message" => "success",
        ], 200);
    }

    protected function getValidatedDate($department_id, $department_class_id, $request, $id = null)
    {
        return $request->validate([
            'name' => [
                'required',
                Rule::unique(DepartmentClassSubject::class, 'name')
                    ->where("department_class_id", $department_class_id)
                    ->ignore($id)
            ],
            'subject_code' => [
                'required',
                Rule::unique(DepartmentClassSubject::class, 'subject_code')
                    ->ignore($id)
            ],
            'book' => [
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
