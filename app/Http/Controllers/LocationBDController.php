<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\District;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationBDController extends Controller
{
    public function divisions(Request $request)
    {
        $divisions = $this->getDivisionData($request);

        return response(compact('divisions'));
    }

    public function divisionDistricts(Request $request, Division $division)
    {
        $districts = $this->getDistrictData($request, $division->id);

        return response(compact('districts'));
    }

    public function districts(Request $request)
    {
        $districts = $this->getDistrictData($request, $request->division);

        return response(compact('districts'));
    }

    public function districtAreas(Request $request, District $district)
    {
        $areas = $this->getAreaData($request, $district->id);

        return response(compact('areas'));
    }

    public function areas(Request $request)
    {
        $areas = $this->getAreaData($request, $request->district, $request->division);

        return response(compact('areas'));
    }

    protected function getDivisionData($request)
    {
        $with = [];

        if ($request->with) {
            $options = explode('.', $request->with);
        
            if ($options[0] == 'districts') {
                $with[] = 'districts:id,name,en_name,division_id';
        
                if (isset($options[1]) && $options[1] == 'areas') {
                    $with[] = 'districts.areas:id,name,en_name,district_id';
                }
            }
        }

        return Division::query()
            ->with($with)
            ->select(DB::raw('id, name, en_name'))
            ->orderBy('en_name')
            ->get([
                'id',
                'name',
                'en_name'
            ]);
    }

    protected function getDistrictData($request, $division_id)
    {
        $with = [];

        if ($request->with) {
            $options = explode(',', $request->with);
            
            if (in_array('division', $options)) {
                $with[] = 'division:id,name,en_name';
            }

            if (in_array('areas', $options)) {
                $with[] = 'areas:id,name,en_name,district_id';
            }
        }

        return District::query()
            ->with($with)
            ->when($division_id, function ($query, $division_id) {
                $query->where('division_id', $division_id);
            })
            ->orderBy('en_name')
            ->get([
                'id',
                'name',
                'en_name',
                'division_id'
            ]);
    }

    protected function getAreaData($request, $district_id, $division_id = null)
    {
        $with = [];

        if ($request->with) {
            $options = explode('.', $request->with);
        
            if ($options[0] == 'district') {
                $with[] = 'district:id,name,en_name,division_id';
        
                if (isset($options[1]) && $options[1] == 'division') {
                    $with[] = 'district.division:id,name,en_name';
                }
            }
        }

        return Area::query()
            ->with($with)
            ->when($district_id, function ($query, $district_id) {
                $query->where('district_id', $district_id);
            })
            ->when($division_id, function ($query, $division_id) {
                $query->whereHas('district', function ($query) use ($division_id) {
                    $query->where('division_id', $division_id);
                });
            })
            ->orderBy('en_name')
            ->get([
                'id',
                'name',
                'en_name',
                'district_id'
            ]);
    }

}
