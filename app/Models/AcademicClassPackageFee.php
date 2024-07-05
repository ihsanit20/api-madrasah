<?php

namespace App\Models;

use App\Traits\HasAuthor;
use App\Traits\HasHistories;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicClassPackageFee extends Model
{
    public static $concessions = [];

    use HasFactory, SoftDeletes, HasAuthor, HasHistories;

    protected $guarded = [];

    protected $appends = [
        'concession',
    ];

    public function getAmountAttribute($value)
    {
        return $value - (self::$concessions[$this->fee_id] ?? 0);
    }

    public function getConcessionAttribute()
    {
        return (self::$concessions[$this->fee_id] ?? 0);
    }

    public function fee()
    {
        return $this->belongsTo(Fee::class);
    }
}
