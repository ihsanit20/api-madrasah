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
            ->with([
                'student.package',
                'student.present_address.area.district',
                'student.guardian_info',
                'academic_class.department_class',
            ])
            ->get();

        $students = $admissions->map(function ($admission) {
            $address = $admission->student->present_address->value ?? '';
            $area_name = $admission->student->present_address->area->name ?? '';
            $district_name = $admission->student->present_address->area->district->name ?? '';

            $full_address = "{$address}, {$area_name}, {$district_name}";

            return [
                'admission_id'  => (int) ($admission->id),
                'full_address'  => (string) ($full_address ?? ''),
                'guardian_phone'=> (string) ($admission->student->guardian_info->phone ?? ''),
                'package_name'  => (string) ($admission->student->package->name ?? ''),
                'class_name'    => (string) ($admission->academic_class->department_class->name ?? ''),
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
