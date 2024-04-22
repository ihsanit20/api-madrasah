<?php

namespace App\Traits\Attributes;

use App\Providers\AppServiceProvider;

trait Gender
{
    public static $gender_array = AppServiceProvider::GENDERS;

    public static function getGenderArrayData()
    {
        return self::$gender_array;
    }

    public function getGender($value)
    {
        return self::$gender_array[$value];
    }

    public function getGenderTextAttribute()
    {
        return self::getGenderArrayData()[$this->gender] ?? '';
    }
}