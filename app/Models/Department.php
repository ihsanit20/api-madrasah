<?php

namespace App\Models;

use App\Traits\HasAuthor;
use App\Traits\HasHistories;
use App\Traits\Scopes\ScopeActive;
use App\Traits\Scopes\ScopeFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes, HasHistories, HasAuthor, ScopeActive, ScopeFilter;

    protected $fillable = [
        'name',
        'is_active',
        'description',
    ];
}
