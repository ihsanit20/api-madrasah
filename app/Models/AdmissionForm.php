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
        'fathers_info'              => 'json',
        'mothers_info'              => 'json',
        'guardian_info'             => 'json',

        'present_address_info'      => 'json',
        'permanent_address_info'    => 'json',

        'previous_info'             => 'json',

        'is_same_address'           => 'bool',
    ];

    protected $appends = [
        'gender_text',
        'blood_group_text',
        'resident_text',
        'application_date',
    ];
}
