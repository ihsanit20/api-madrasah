<?php

namespace App\Models;

use App\Traits\HasAuthor;
use App\Traits\HasHistories;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory, HasHistories, HasAuthor;

    protected $fillable = [
        'name',
        'period',
    ];

    public function scopePeriod($query, $period): void
    {
        $query->where('period', $period);
    }
}
