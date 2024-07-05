<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentDetail extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;
    
    protected $guarded = [];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
