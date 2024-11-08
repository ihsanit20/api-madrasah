<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class FeeName extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'added_by'];

    /**
     * Get the user who added the fee name.
     */
    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
