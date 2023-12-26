<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', 1);
    }

    protected function domain(): Attribute
    {
        $pattern = '/^http(|s)\:\/\/(www\.|)|www./';

        return Attribute::make(
            get: fn (string $value) => rtrim(preg_replace($pattern, '', $value), '/'),
            set: fn (string $value) => rtrim(preg_replace($pattern, '', $value), '/'),
        );
    }
}
