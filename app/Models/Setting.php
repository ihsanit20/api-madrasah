<?php

namespace App\Models;

use App\Traits\HasHistories;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    use HasFactory, SoftDeletes, HasHistories;

    protected $guarded = [];

    protected $casts = [
        "value" => "json",
    ];

    public function scopeProperty($query, string $property): void
    {
        $query->where('property', $property);
    }

    public function scopeProperties($query, array $properties = []): void
    {
        $query->whereIn('property', $properties);
    }
}
