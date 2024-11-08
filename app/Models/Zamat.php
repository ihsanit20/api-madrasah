<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zamat extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'section_id',
        'added_by',
        'name',
        'description',
        'is_active',
        'priority'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Section relationship
     */
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    /**
     * Added by relationship
     */
    public function added_by()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
