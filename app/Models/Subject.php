<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'zamat_id', 'added_by', 'name', 'book_name', 'subject_code', 'is_active', 'priority'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function zamat()
    {
        return $this->belongsTo(Zamat::class);
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
