<?php

namespace App\Models;

use App\Traits\HasAuthor;
use App\Traits\HasHistories;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicClassPackageFee extends Model
{
    use HasFactory, SoftDeletes, HasAuthor, HasHistories;

    protected $guarded = [];
}
