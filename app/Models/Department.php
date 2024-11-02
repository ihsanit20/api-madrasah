<?php

namespace App\Models;

use App\Traits\HasAuthor;
use App\Traits\HasHistories;
use App\Traits\Scopes\ScopeActive;
use App\Traits\Scopes\ScopeFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes, HasHistories, HasAuthor, ScopeActive, ScopeFilter;

    protected $fillable = [
        'name',
        'is_active',
        'description',
        'author_id',  // Ensure author_id is fillable
    ];

    public function department_classes(): HasMany
    {
        return $this->hasMany(DepartmentClass::class)
            ->orderBy('priority');
    }

    public function academic_sessions(): HasMany
    {
        return $this->hasMany(AcademicSession::class)
            ->orderBy('priority');
    }

    // Define the user relationship
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
