<?php

namespace App\Http\Controllers;

use App\Http\Resources\AcademicSubjectCollection;
use App\Models\AcademicClass;
use App\Models\AcademicSession;
use App\Models\Department;
use App\Models\DepartmentClassSubject;
use Illuminate\Http\Request;

class DepartmentAcademicSessionAcademicClassAcademicSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Department $department, AcademicSession $academic_session, AcademicClass $academic_class)
    {
        if($department->id != $academic_session->department_id || $academic_session->id != $academic_class->academic_session_id) {
            return $this->notFound();
        }

        AcademicSubjectCollection::wrap('academic_subjects');

        return AcademicSubjectCollection::make(
            $academic_class->academic_subjects()
                ->with([
                    'department_class_subject:id,name'
                ])
                ->oldest('priority')
                ->paginate(request()->per_page)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Department $department, AcademicSession $academic_session, AcademicClass $academic_class)
    {
        if($department->id != $academic_session->department_id || $academic_session->id != $academic_class->academic_session_id) {
            return $this->notFound();
        }

        // return
        $active_department_class_subject_ids = DepartmentClassSubject::query()
            ->where([
                'department_class_id'   => $academic_class->department_class_id,
                'is_active'             => 1,
            ])
            ->pluck('id')
            ->toArray();

        // return
        $filtered_department_class_subject_ids = array_intersect($request->department_class_subject_ids ?? [], $active_department_class_subject_ids);

        // return
        $academic_class->academic_subjects()
            ->whereNotIn("department_class_subject_id", $filtered_department_class_subject_ids)
            ->delete();

        foreach($filtered_department_class_subject_ids as $index => $department_class_subject_id) {
            $academic_class->academic_subjects()
                ->withTrashed()
                ->updateOrCreate(
                    [
                        "department_class_subject_id"   => $department_class_subject_id
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
        $academic_subjects = $academic_class->academic_subjects()
            ->oldest('priority')
            ->get();

        return response([
            "academic_subjects" => $academic_subjects
                ? AcademicSubjectCollection::make($academic_subjects)
                : [],
        ], 201);
    }
}
