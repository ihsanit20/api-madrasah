<?php

namespace App\Http\Controllers;

use App\Http\Resources\PackageFeeCollection;
use App\Models\AcademicClass;
use App\Models\AcademicClassPackageFee;
use App\Models\AcademicSession;
use App\Models\Department;
use App\Models\Fee;
use App\Models\Package;
use Illuminate\Http\Request;

class DepartmentAcademicSessionAcademicClassPackageFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Department $department, AcademicSession $academic_session, AcademicClass $academic_class)
    {
        if($department->id != $academic_session->department_id || $academic_session->id != $academic_class->academic_session_id) {
            return $this->notFound();
        }

        $academic_class_package_fees = AcademicClassPackageFee::query()
            ->where([
                'academic_class_id' => $academic_class->id,
            ])
            ->get();

        return response([
            "academic_class_package_fees" => $academic_class_package_fees
                ? PackageFeeCollection::make($academic_class_package_fees)
                : [],
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Department $department, AcademicSession $academic_session, AcademicClass $academic_class)
    {
        if($department->id != $academic_session->department_id || $academic_session->id != $academic_class->academic_session_id) {
            return $this->notFound();
        }

        // return $request->academic_class_package_fees;

        // return
        $active_package_ids = Package::query()
            ->where([
                'is_active' => 1,
            ])
            ->pluck('id')
            ->toArray();

        $active_fee_ids = Fee::query()
            ->pluck('id')
            ->toArray();

        $filtered_academic_class_package_fee_ids = [];

        foreach ($request->academic_class_package_fees as $item)
        {
            // $item => {package_id: 1, fee_id: 5, amount: 100}

            if (in_array($item['package_id'], $active_package_ids))
            {
                if (in_array($item['fee_id'], $active_fee_ids))
                {
                    $academic_class_package_fee = AcademicClassPackageFee::withTrashed()
                        ->updateOrCreate(
                            [
                                'academic_class_id' => $academic_class->id,
                                'package_id'        => $item['package_id'],
                                'fee_id'            => $item['fee_id'],
                            ],
                            [
                                'amount'        => $item['amount'],
                                'deleted_at'    => null,
                                'author_id'     => auth()->id() ?? null,
                            ]
                        );

                    $filtered_academic_class_package_fee_ids[] = $academic_class_package_fee->id;
                }
            }
        }

        AcademicClassPackageFee::query()
            ->where('academic_class_id', $academic_class->id)
            ->whereNotIn('id', $filtered_academic_class_package_fee_ids)
            ->update([
                'deleted_at'    => now(),
                'author_id'     => auth()->id() ?? null,
            ]);

        // return 
        $academic_class_package_fees = AcademicClassPackageFee::query()
            ->where([
                'academic_class_id' => $academic_class->id,
            ])
            ->get();

        return response([
            "academic_class_package_fees" => $academic_class_package_fees 
                ? PackageFeeCollection::make($academic_class_package_fees)
                : [],
        ], 201);
    }
}
