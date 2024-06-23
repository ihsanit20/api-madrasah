<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admission extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'concessions' => 'json',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function academic_class()
    {
        return $this->belongsTo(AcademicClass::class);
    }
}
