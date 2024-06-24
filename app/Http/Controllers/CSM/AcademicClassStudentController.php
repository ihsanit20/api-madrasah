<?php

namespace App\Http\Controllers\CSM;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\AcademicClassPackageFee;
use App\Models\Admission;
use App\Models\Student;
use Illuminate\Http\Request;

class AcademicClassStudentController extends Controller
{
    public function show(AcademicClass $academic_class, Student $student)
    {
        $admission = Admission::query()
            // ->with('academic_session')
            ->where([
                'academic_class_id' => $academic_class->id,
                'student_id'        => $student->id,
            ])
            ->firstOrFail();

        // return
        $academic_session = $admission->academic_session()->first();

        // return
        $months = $this->getAcademicSessionMonths($academic_session);

        // return
        $academic_class_package_fees = AcademicClassPackageFee::query()
            ->with('fee:id,name,period')
            ->where([
                'academic_class_id' => $academic_class->id,
                'package_id'        => $student->package_id,
            ])
            ->get()
            ->groupBy(function($item) {
                return $item->fee->period;
            });

        $annual_total = $academic_class_package_fees->get(2)?->sum('amount') ?? 0;
        $monthly_total = $academic_class_package_fees->get(1)?->sum('amount') ?? 0;

        // Prepare the fees array
        $fees = [
            [
                'period'    => 2,
                'month'     => $months[0],
                'name'      => "Admission & Annual Fee",
                'amount'    => $annual_total,
            ],
        ];
        
        foreach ($months as $month) {
            $fees[] = [
                'period'    => 1,
                'month'     => $month,
                'name'      => "Fee of {$month}",
                'amount'    => $monthly_total,
            ];
        }

        $admission->load([
            'academic_class.department_class'
        ]);

        $student->load([
            'present_address.area.district',
            'guardian_info',
            'package',
        ]);

        $address = $student->present_address->value ?? '';
        $area_name = $student->present_address->area->name ?? '';
        $district_name = $student->present_address->area->district->name ?? '';

        $full_address = "{$address}, {$area_name}, {$district_name}";

        return response([
            'admission_id'  => (int) ($admission->id),
            'student_id'    => (int) ($student->id),
            'student'   => [
                'id'            => (int) ($student->id ?? 0),
                'name'          => (string) ($student->name ?? ''),
                'photo'         => (string) ($student->photo ?? ''),
                'roll'          => (int) ($admission->roll ?? 0),

                'registration'  => (string) ($student->registration ?? ''),
                'full_address'  => (string) ($full_address ?? ''),
                'guardian_phone'=> (string) ($student->guardian_info->phone ?? ''),
                'package_name'  => (string) ($student->package->name ?? ''),
                'class_name'    => (string) ($admission->academic_class->department_class->name ?? ''),
            ],
            'fees'      => $fees,
        ]);
    }
}
