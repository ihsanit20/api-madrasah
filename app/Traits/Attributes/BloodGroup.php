<?php

namespace App\Traits\Attributes;

use App\Providers\AppServiceProvider;

trait BloodGroup
{
    public static $blood_groups = AppServiceProvider::BLOOD_GROUPS;

    public static function getBloodGroups()
    {
        return self::$blood_groups;
    }

    public function getBloodGroup($value)
    {
        return self::$blood_groups[$value];
    }

    public function getBloodGroupTextAttribute()
    {
        return self::$blood_groups[$this->blood_group ?? 0] ?? '';
    }
}