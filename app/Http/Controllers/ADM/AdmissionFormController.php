<?php

namespace App\Http\Controllers\ADM;

use App\Http\Controllers\Controller;
use App\Http\Resources\PackageFeeCollection;
use App\Models\AcademicClassPackageFee;
use App\Models\AdmissionForm;
use Illuminate\Http\Request;

class AdmissionFormController extends Controller
{
    public function index()
    {
        return response([
            'admission_forms' => AdmissionForm::query()
                ->with([
                    'academic_class.department_class:id,name'
                ])
                ->get()
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'academic_session_id'       => 'required|numeric',
            'admission_type'            => 'required|string',
            "basic_info"                => 'required|array',
            "father_info"               => 'required|array',
            "mother_info"               => 'required|array',
            "guardian_info"             => 'required|array',
            "present_address_info"      => 'required|array',
            "permanent_address_info"    => 'required|array',
            "previous_info"             => 'required|array',
            "academic_class_id"         => 'required|numeric',
            "package_id"                => 'required|numeric',
        ]);

        // return response($data);

        $admission_form = AdmissionForm::create($data);

        return response($admission_form ?? (object) [], 201);
    }

    public function show(AdmissionForm $admission_form)
    {
        return response($admission_form);

        return response([
            "admission_form_id"     => 3,
            "admission_type"        => "new",
            "academic_session_id"   => 1,
            "student_photo" => [
                "link" => ""
            ],
            "basic_info" => [
                "name"              => "Student Name",
                "gender"            => "",
                "blood_group"       => "",
                "date_of_birth"     => "",
                "birth_certificate" => ""
            ],
            
            "parental_info" => [
                "father_info" => [
                    "name"          => "",
                    "phone"         => "",
                    "occupation"    => ""
                ],
                    "mother_info"   => [
                    "name"          => "",
                    "phone"         => "",
                    "occupation"    => ""
                ]
            ],
            
            "guardian_info" => [
                "type"      => "",
                "name"      => "",
                "phone"     => "",
                "relation"  => ""
            ],
            "address_info" => [
                "present_address_info"      => [],
                "is_same_address"           => false,
                "permanent_address_info"    => []
            ],
            "previous_info" => [
                "student_type"      => "",
                "institute"         => "",
                "academic_class"    => "",
                "roll"              => "",
                "exam"              => "",
                "result"            => ""
            ],
            "expectation_info" => [
                "academic_class_id" => "",
                "package_id"        => ""
            ], 
        ]);
    }

    public function update(Request $request, AdmissionForm $admission_form)
    {
        $columns = [
            'basic_info',
            'father_info',
            'mother_info',
            'guardian_info',
            'previous_info',
            'previous_info',
            'present_address_info',
            'permanent_address_info',
            'academic_class_id',
            'package_id',
        ];

        $column = '';
        
        foreach ($columns as $col) {
            if ($request->$col) {
                $column = $col;
                break;
            }
        }
        
        if($column) {
            $admission_form->update([
                $column => $request->$column
            ]);

            return response([
                $column => $admission_form->$column 
            ]);
        }

        return response([]);
    }

    public function admissionTestShow(AdmissionForm $admission_form)
    {
        // return
        $admission_form->load([
            'academic_class:id,department_class_id',
            'academic_class.department_class:id,name',
            'package:id,name',
        ]);

        return response([
            "admission_form_id"     => (int) ($admission_form->id),
            "student_name"          => (string) ($admission_form->basic_info["name"] ?? ""),
            "academic_class_name"   => (string) ($admission_form->academic_class->department_class->name ?? ""),
            "package_name"          => (string) ($admission_form->package->name ?? ""),
            "admission_test"        => (object) ($admission_form->admission_test ?? []),
        ]);
    }

    public function admissionTestUpdate(Request $request, AdmissionForm $admission_form)
    {
        $request->validate([
            'admission_test' => 'required',
        ]);

        // return
        $admission_form->update([
            'admission_test' => $request->admission_test,
        ]);

        // return
        $admission_form->load([
            'academic_class:id,department_class_id',
            'academic_class.department_class:id,name',
            'package:id,name',
        ]);

        return response([
            "admission_form_id"     => (int) ($admission_form->id),
            "student_name"          => (string) ($admission_form->basic_info["name"] ?? ""),
            "academic_class_name"   => (string) ($admission_form->academic_class->department_class->name ?? ""),
            "package_name"          => (string) ($admission_form->package->name ?? ""),
            "admission_test"        => (object) ($admission_form->admission_test ?? []),
        ]);
    }

    public function admissionFeeShow(AdmissionForm $admission_form)
    {
        // return
        $admission_form->load([
            'academic_class:id,department_class_id',
            'academic_class.department_class:id,name',
            'package:id,name',
        ]);

        // return
        $academic_class_package_fees = AcademicClassPackageFee::query()
            ->with('fee:id,name,period')
            ->where([
                'academic_class_id' => $admission_form->academic_class_id,
                'package_id'        => $admission_form->package_id,
            ])
            ->get();

        $annual_fees = [];
        $monthly_fees = [];

        foreach($academic_class_package_fees as $academic_class_package_fee) {
            $data = [
                "id"        => (int) ($academic_class_package_fee->fee_id ?? 0),
                "amount"    => (double) ($academic_class_package_fee->amount ?? 0),
                "name"      => (string) ($academic_class_package_fee->fee->name ?? ""),
            ];

            if($academic_class_package_fee->fee->period == 2) {
                $annual_fees[] = $data;
            } else {
                $monthly_fees[] = $data;
            }
        }

        return response([
            "admission_form_id"     => (int) ($admission_form->id),
            "student_name"          => (string) ($admission_form->basic_info["name"] ?? ""),
            "academic_class_name"   => (string) ($admission_form->academic_class->department_class->name ?? ""),
            "package_name"          => (string) ($admission_form->package->name ?? ""),

            "concessions"           => (object) ($admission_form->concessions ?? []),

            "annual_fees"           => (array) ($annual_fees ?? []),
            "monthly_fees"          => (array) ($monthly_fees ?? []),
        ]);
    }

    public function admissionFeeUpdate(Request $request, AdmissionForm $admission_form)
    {
        $request->validate([
            'concessions' => 'required',
        ]);

        // return
        $admission_form->update([
            'concessions' => $request->concessions,
        ]);

        // return
        $admission_form->load([
            'academic_class:id,department_class_id',
            'academic_class.department_class:id,name',
            'package:id,name',
        ]);

        // return
        $academic_class_package_fees = AcademicClassPackageFee::query()
            ->with('fee:id,name,period')
            ->where([
                'academic_class_id' => $admission_form->academic_class_id,
                'package_id'        => $admission_form->package_id,
            ])
            ->get();

        $annual_fees = [];
        $monthly_fees = [];

        foreach($academic_class_package_fees as $academic_class_package_fee) {
            $data = [
                "id"        => (int) ($academic_class_package_fee->fee_id ?? 0),
                "amount"    => (double) ($academic_class_package_fee->amount ?? 0),
                "name"      => (string) ($academic_class_package_fee->fee->name ?? ""),
            ];

            if($academic_class_package_fee->fee->period == 2) {
                $annual_fees[] = $data;
            } else {
                $monthly_fees[] = $data;
            }
        }

        return response([
            "admission_form_id"     => (int) ($admission_form->id),
            "student_name"          => (string) ($admission_form->basic_info["name"] ?? ""),
            "academic_class_name"   => (string) ($admission_form->academic_class->department_class->name ?? ""),
            "package_name"          => (string) ($admission_form->package->name ?? ""),
            
            "concessions"           => (object) ($admission_form->concessions ?? []),

            "annual_fees"           => (array) ($annual_fees ?? []),
            "monthly_fees"          => (array) ($monthly_fees ?? []),
        ]);
    }

    public function admissionCompletionShow(AdmissionForm $admission_form)
    {
        // return
        $admission_form->load([
            'academic_class:id,department_class_id',
            'academic_class.department_class:id,name',
            'package:id,name',
        ]);

        // return
        $academic_class_package_fees = AcademicClassPackageFee::query()
            ->with('fee:id,name,period')
            ->where([
                'academic_class_id' => $admission_form->academic_class_id,
                'package_id'        => $admission_form->package_id,
            ])
            ->get();

        return response([
            "admission_form_id"     => (int) ($admission_form->id),
            "student_name"          => (string) ($admission_form->basic_info["name"] ?? ""),
            "academic_class_name"   => (string) ($admission_form->academic_class->department_class->name ?? ""),
            "package_name"          => (string) ($admission_form->package->name ?? ""),
        ]);
    }

    public function admissionCompletionUpdate(Request $request, AdmissionForm $admission_form)
    {
        /**
         * Create Student for new admission from admission form
         * Create Admission from admission form
         */

        // return
        $admission_form->load([
            'academic_class:id,department_class_id',
            'academic_class.department_class:id,name',
            'package:id,name',
        ]);

        if ($admission_form->status != 2) {
            if($admission_form->type == 'new') {
                // Create Student for new admission

            }

            // Create Admission 
        }

        return response([
            "admission_form_id"     => (int) ($admission_form->id),
            "student_name"          => (string) ($admission_form->basic_info["name"] ?? ""),
            "academic_class_name"   => (string) ($admission_form->academic_class->department_class->name ?? ""),
            "package_name"          => (string) ($admission_form->package->name ?? ""),
        ]);
    }
}
