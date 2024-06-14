<?php

namespace App\Http\Controllers\CSM;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\AcademicSession;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        return response([
            'departments' => Department::query()
                ->has('academic_sessions')
                ->with('academic_sessions')
                ->get(),
        ]);
    }

    public function show(AcademicSession $academicSession)
    {
        return response([
            'academic_classes' => AcademicClass::query()
                ->with('department_class')
                ->where('academic_session_id', $academicSession->id)
                ->get(),
        ]);
    }
}
