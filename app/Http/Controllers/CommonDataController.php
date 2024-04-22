<?php

namespace App\Http\Controllers;

use App\Providers\AppServiceProvider;
use Illuminate\Http\Request;

class CommonDataController extends Controller
{
    public function getIdNamePairsFromArray($array)
    {
        return array_map(
            function ($id, $name) {
                return compact('id', 'name');
            },
            array_keys($array),
            array_values($array)
        );
    }
    
    public function getBloodGroup()
    {
        return response([
            'blood_groups' => $this->getIdNamePairsFromArray(AppServiceProvider::BLOOD_GROUPS),
        ]);
    }
    
    public function getGenders()
    {
        return response([
            'genders' => $this->getIdNamePairsFromArray(AppServiceProvider::GENDERS),
        ]);
    }
}
