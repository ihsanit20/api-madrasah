<?php

namespace App\Models;

use App\Traits\Attributes\BloodGroup;
use App\Traits\Attributes\Gender;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes, BloodGroup, Gender;

    protected $guarded = [];
    
    protected $appends = [
        'age',
        'gender_text',
        'blood_group_text',
        'guardian_type',
        'is_same_address',
    ];
    
    protected $casts = [
        'date_of_birth' => 'date',
    ];
    
    public function getPhotoAttribute()
    {
        return "https://ui-avatars.com/api/?length=1&name={$this->name}";
    }
    
    public function getAgeAttribute()
    {
        if ($this->date_of_birth) {
            return Carbon::parse($this->date_of_birth)->age;
        }

        return 0;
    }

    public function getGuardianTypeAttribute()
    {
        if($this->father_info_id == $this->guardian_info_id) {
            return 1;
        }

        if($this->mother_info_id == $this->guardian_info_id) {
            return 2;
        }

        return 3;
    }

    public function getIsSameAddressAttribute()
    {
        return (boolean) ($this->present_address_id == $this->permanent_address_id);
    }

    public function father_info()
    {
        return $this->belongsTo(Guardian::class, 'father_info_id');
    }

    public function mother_info()
    {
        return $this->belongsTo(Guardian::class, 'mother_info_id');
    }

    public function guardian_info()
    {
        return $this->belongsTo(Guardian::class, 'guardian_info_id');
    }

    public function present_address()
    {
        return $this->belongsTo(Address::class, 'present_address_id');
    }

    public function permanent_address()
    {
        return $this->belongsTo(Address::class, 'permanent_address_id');
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }
}
