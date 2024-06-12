<?php

namespace App\Http\Controllers\ADM;

use App\Http\Controllers\Controller;
use App\Http\Resources\PackageFeeCollection;
use App\Models\AcademicClassPackageFee;
use App\Models\Address;
use App\Models\AdmissionForm;
use App\Models\Student;
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
            $student = Student::find((int) ($admission_form->student_id ?? 0));

            $type = $admission_form->type === 'old' ? 'old' : 'new';

            if($student && $type == 'old') {
                $student->update(
                    $this->validatedStudentData($request, $student->id)
                    + $this->storeGuardian($request, $student)
                    + $this->storeAddress($request, $student)
                );
            } else {
                $student = Student::create(
                    $this->validatedStudentData($request)
                    + $this->storeGuardian($request)
                    + $this->storeAddress($request)
                );
            }

            $admission = $student->admissions()->create(
                $this->validatedAdmissionData($request)
                + $this->getArrayOfSession($request->session)
            );
        }

        return response([
            "admission_form_id"     => (int) ($admission_form->id),
            "student_name"          => (string) ($admission_form->basic_info["name"] ?? ""),
            "academic_class_name"   => (string) ($admission_form->academic_class->department_class->name ?? ""),
            "package_name"          => (string) ($admission_form->package->name ?? ""),
            "student"               => $student,
            "admission"             => $admission,
        ]);
    }

    protected function validatedStudentData($request, $id = '')
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
            ],
            'package_id' => [
                'required',
            ],
            'date_of_birth'     => '',
            'gender'            => '',
            'birth_certificate' => '',
            'blood_group'       => '',
        ]);
    }

    protected function validatedAdmissionData($request, $id = '')
    {
        return $request->validate([
            'academic_class_id' => [
                'required',
                'numeric',
            ],
        ]);
    }

    protected function storeGuardian($request, $student = null)
    {
        if($student) {
            Guardian::query()
                ->whereIn('id', [
                    $student->father_info_id,
                    $student->mother_info_id,
                    $student->guardian_info_id
                ])
                ->delete();
        }

        $father_info_id = $this->storeGuardianGetId($request->father_info);

        $mother_info_id = $this->storeGuardianGetId($request->mother_info);

        if ($request->guardian_type == 1) {
            $guardian_info_id = $father_info_id;
        } 
        elseif ($request->guardian_type == 2) {
            $guardian_info_id = $mother_info_id;
        }
        else {
            $guardian_info_id = $this->storeGuardianGetId($request->guardian_info);
        }

        return [
            'father_info_id'    => $father_info_id,
            'mother_info_id'    => $mother_info_id,
            'guardian_info_id'  => $guardian_info_id,
        ];
    }

    protected function storeAddress($request, $student = null)
    {
        if($student) {
            Address::query()
                ->whereIn('id', [
                    $student->present_address_id,
                    $student->permanent_address_id
                ])
                ->delete();
        }

        $present_address_id = $this->storeAddressGetId($request->present_address);

        $permanent_address_id = $request->is_same_address
            ? $present_address_id
            : $this->storeAddressGetId($request->permanent_address);

        return [
            'present_address_id'    => $present_address_id,
            'permanent_address_id'  => $permanent_address_id,
        ];
    }

    protected function storeGuardianGetId($guardian, $old_id = '')
    {
        $response = Guardian::onlyTrashed()->updateOrCreate(
            [],
            [
                'name'          => $guardian['name'] ?? null,
                'phone'         => $guardian['phone'] ?? null,
                'occupation'    => $guardian['occupation'] ?? null,
                'relation'      => $guardian['relation'] ?? null,
                'deleted_at'    => null,
            ]
        );

        return $response->id ?? null;
    }

    protected function storeAddressGetId($address, $old_id = '')
    {
        if($old_id) {
            Address::where('id', $old_id)->delete();
        }

        $response = Address::onlyTrashed()->updateOrCreate(
            [],
            [
                'area_id'       => $address['area'] ?? null,
                'value'         => $address['address'] ?? null,
                'postoffice'    => $address['postoffice'] ?? null,
                'deleted_at'    => null,
            ]
        );

        return $response->id ?? null;
    }

    protected function getNewClassRoll($class_id, $session = null)
    {
        return  $this->getLastClassRoll($class_id, $session) + 1;
    }

    protected function getLastClassRoll($class_id, $session = null)
    {
        $session = $session_id ?? $this->getCurrentSession();

        $last_class_roll = Admission::query()
            ->where([
                'class_id'      => $class_id,
                'session'       => $session
            ])
            ->max('roll');

        return $last_class_roll ?? 0;
    }

    protected function getArrayOfSession($session = null)
    {
        return [
            "session" => $session ?? $this->getCurrentSession()
        ];
    }
}
