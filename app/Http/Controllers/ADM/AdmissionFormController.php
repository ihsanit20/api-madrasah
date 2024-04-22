<?php

namespace App\Http\Controllers\ADM;

use App\Http\Controllers\Controller;
use App\Models\AdmissionForm;
use Illuminate\Http\Request;

class AdmissionFormController extends Controller
{
    const INSTITUTE_PROPERTIES = [
        "student_photo" => "student_photo",
        "basic_info" => "basic_info",
    ];

    public function index()
    {
        return response([
            'admission_forms' => AdmissionForm::get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'academic_session_id'   => 'required|numeric',
            'admission_type'        => 'required',
        ]);

        $admission_form = AdmissionForm::create([
            'academic_session_id'   => $request->academic_session_id,
            'admission_type'        => $request->admission_type,
        ]);

        return response([
            'admission_form' => $admission_form ?? (object) []
        ], 201);
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
}
