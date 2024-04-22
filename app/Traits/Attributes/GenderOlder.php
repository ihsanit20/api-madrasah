<?php

namespace App\Traits\Attributes;

trait GenderOlder
{
    public static $gender_array = [
        1 => 'পুরুষ',
        2 => 'মহিলা',
    ];

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