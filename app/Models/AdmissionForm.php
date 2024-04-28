<?php

namespace App\Models;

use App\Traits\Attributes\BloodGroup;
use App\Traits\Attributes\Gender;
use App\Traits\HasHistories;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdmissionForm extends Model
{
    use HasFactory, SoftDeletes, HasHistories, BloodGroup, Gender;

    protected $guarded = [];

    protected $casts = [
        'student_photo'             => 'json',

        'basic_info'                => 'json',

        'father_info'               => 'json',
        'mother_info'               => 'json',
        'guardian_info'             => 'json',

        'present_address_info'      => 'json',
        'is_same_address'           => 'bool',
        'permanent_address_info'    => 'json',

        'previous_info'             => 'json',
    ];

    protected $appends = [
        'gender_text',
        'blood_group_text',
        'admission_form_id',
        'application_date',
    ];

    public function getAdmissionFormIdAttribute()
    {
        return (int) ($this->id);
    }

    public function getApplicationDateAttribute()
    {
        return $this->created_at
            ? $this->created_at->format('Y-m-d')
            : "";
    }

    public function academic_class()
    {
        return $this->belongsTo(AcademicClass::class);
    }
}
