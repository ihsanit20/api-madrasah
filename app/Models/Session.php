<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Session extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'section_id',
        'name',
        'starting_date',
        'ending_date',
        'is_active',
        'added_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starting_date' => 'date',
        'ending_date' => 'date',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
