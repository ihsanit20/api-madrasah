<?php

namespace App\Http\Controllers\CSM;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\AcademicClassPackageFee;
use App\Models\Admission;
use App\Models\PaymentDetail;
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

        $paid_monthly_months = PaymentDetail::query()
            ->whereHas('payment', function ($query) use ($admission) {
                $query->where('admission_id', $admission->id);
            })
            ->where('period', 1)
            ->pluck('month')
            ->toArray();

        $paid_annual_fee_ids = PaymentDetail::query()
            ->whereHas('payment', function ($query) use ($admission) {
                $query->where('admission_id', $admission->id);
            })
            ->where('period', 2)
            ->pluck('fee_id')
            ->toArray();

        // return
        $academic_class_package_fees = AcademicClassPackageFee::query()
            ->with('fee:id,name,period')
            ->has('fee')
            ->where([
                'academic_class_id' => $academic_class->id,
                'package_id'        => $student->package_id,
            ])
            ->get()
            ->groupBy(function($item) {
                return $item->fee->period;
            });

        // return $academic_class_package_fees->get(2);

        $monthly_total = $academic_class_package_fees->get(1)?->sum('amount') ?? 0;

        // Prepare the fees array
        $fees = [];
        $annual_fees = [];
        $monthly_fees = [];

        foreach($academic_class_package_fees->get(2) as $academic_class_package_fee) {
            $fee_id = $academic_class_package_fee?->fee_id ?? '';

            if(!$fee_id || in_array($fee_id, $paid_annual_fee_ids)) {
                continue;
            }

            $data = [
                'fee_id'    => (int) ($fee_id),
                'period'    => (int) (2),
                'month'     => (string) (""),
                'name'      => (string) ($academic_class_package_fee?->fee?->name ?? ''),
                'amount'    => (int) ($academic_class_package_fee?->amount ?? 0),
            ];

            $fees[] = $data;
            $annual_fees[] = $data;
        }
        
        foreach ($months as $month_value => $month_text) {
            if(in_array($month_value, $paid_monthly_months)) {
                continue;
            }

            $data = [
                'fee_id'    => (int) (0),
                'period'    => (int) (1),
                'month'     => (string) ($month_value),
                'name'      => (string) ("Fee of {$month_text}"),
                'amount'    => (int) ($monthly_total ?? 0),
            ];

            $fees[] = $data;
            $monthly_fees[] = $data;
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
                'registration'  => (string) ($student->registration ?? ''),
                'account'       => (double) ($student->account ?? 0),
                
                'roll'          => (int) ($admission->roll ?? 0),
                'full_address'  => (string) ($full_address ?? ''),
                'guardian_phone'=> (string) ($student->guardian_info->phone ?? ''),
                'package_name'  => (string) ($student->package->name ?? ''),
                'class_name'    => (string) ($admission->academic_class->department_class->name ?? ''),
            ],
            'fees'          => $fees,
            'annual_fees'   => $annual_fees,
            'monthly_fees'  => $monthly_fees,
        ]);
    }
}
