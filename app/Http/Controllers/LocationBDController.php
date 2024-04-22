<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\District;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationBDController extends Controller
{
    public function divisions()
    {
        $divisions = Division::query()
            ->select(DB::raw('id, en_name as name'))
            ->get();

        return response(compact('divisions'));
    }

    public function divisionDistricts(Division $division)
    {
        $districts = District::query()
            ->where('division_id', $division->id)
            ->select(DB::raw('id, en_name as name'))
            ->get();

        return response(compact('districts'));
    }

    public function districtAreas(District $district)
    {
        $areas = Area::query()
            ->where('district_id', $district->id)
            ->select(DB::raw('id, en_name as name'))
            ->get();

        return response(compact('areas'));
    }

}
