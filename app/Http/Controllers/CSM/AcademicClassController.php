<?php

namespace App\Http\Controllers\CSM;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use Illuminate\Http\Request;

class AcademicClassController extends Controller
{
    public function students(AcademicClass $academic_class)
    {
        // return
        $admissions = $academic_class->admissions()
            ->with('student')
            ->get();

        $students = $admissions->map(function ($admission) {
            return [
                'admission_id'  => (int) ($admission->id),
                'id'            => (int) ($admission->student->id ?? 0),
                'name'          => (string) ($admission->student->name ?? ''),
                'photo'         => (string) ($admission->student->photo ?? ''),
                'roll'          => (int) ($admission->roll ?? 0),
                'registration'  => (string) ($admission->student->registration ?? ''),
            ];
        });

        return response([
            'students' => $students,
        ]);
    }
}
