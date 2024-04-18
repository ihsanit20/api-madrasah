<?php

namespace App\Http\Controllers;

use App\Http\Resources\AcademicClassCollection;
use App\Http\Resources\AcademicClassResource;
use App\Models\AcademicClass;
use App\Models\AcademicSession;
use App\Models\Department;
use App\Models\DepartmentClass;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentAcademicSessionAcademicClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Department $department, AcademicSession $academic_session)
    {
        if($academic_session->department_id != $department->id) {
            return $this->notFound();
        }

        // return 
        $academic_classes = $academic_session->academic_classes()
            ->oldest('priority')
            ->get();

        return response([
            "academic_classes" => $academic_classes,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Department $department, AcademicSession $academic_session)
    {
        if($academic_session->department_id != $department->id) {
            return $this->notFound();
        }

        // return
        $active_department_class_ids = DepartmentClass::query()
            ->where([
                'department_id' => $academic_session->department_id,
                'is_active'     => 1,
            ])
            ->pluck('id')
            ->toArray();

        // return
        $filtered_department_class_ids = array_values(
            array_intersect($request->department_classes ?? [], $active_department_class_ids)
        );

        // return
        $academic_session->academic_classes()
            ->whereNotIn("department_class_id", $filtered_department_class_ids)
            ->delete();

        foreach($filtered_department_class_ids as $index => $department_class_id) {
            $academic_session->academic_classes()
                ->withTrashed()
                ->updateOrCreate(
                    [
                        "department_class_id"   => $department_class_id
                    ],
                    [
                        // "author_id"     => auth()->id() ?? null,
                        "priority"      => $index,
                        "is_active"     => 1,
                        "deleted_at"    => null,
                    ]
                );
        }

        // return 
        $academic_classes = $academic_session->academic_classes()
            ->oldest('priority')
            ->get();

        return response([
            "academic_classes" => $academic_classes
                ? AcademicClassCollection::make($academic_classes)
                : [],
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department, AcademicSession $academic_session, $academic_class_id)
    {
        if($academic_session->department_id != $department->id) {
            return $this->notFound();
        }

        $academic_class = $academic_session->academic_classes()
            ->with('author')
            ->where('id', $academic_class_id)
            ->first();

        // return $academic_class;

        return response([
            "academic_class" => $academic_class
                ? AcademicClassResource::make($academic_class)
                : (object) ([]),
        ], 200);
    }
}
