<?php

namespace App\Models;

use App\Traits\HasAuthor;
use App\Traits\HasHistories;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicClass extends Model
{
    use HasFactory, SoftDeletes, HasAuthor, HasHistories;

    protected $guarded = [];

    public function academic_session(): BelongsTo
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function department_class(): BelongsTo
    {
        return $this->belongsTo(DepartmentClass::class);
    }
}
