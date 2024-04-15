<?php

namespace App\Http\Controllers;

use App\Http\Resources\FeeCollection;
use App\Http\Resources\FeeResource;
use App\Models\Fee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MonthlyFeeController extends Controller
{
    const PERIOD = 1;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        FeeCollection::wrap('fees');

        return FeeCollection::make(Fee::with('author:id,name')->period(self::PERIOD)->paginate(request()->per_page));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $this->getValidatedDate($request);

        $fee = Fee::create(
            $this->getValidatedDate($request) + [
                'period' => self::PERIOD,
            ]
        );

        return response([
            "fee" => FeeResource::make($fee),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Fee $monthly_fee)
    {
        if($monthly_fee->period != self::PERIOD) {
            return response('Not Found!', 404);
        }

        return response([
            "fee" => FeeResource::make($monthly_fee),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fee $monthly_fee)
    {
        if($monthly_fee->period != self::PERIOD) {
            return response('Not Found!', 404);
        }

        // return $this->getValidatedDate($request, $fee->id);

        $monthly_fee->update($this->getValidatedDate($request, $monthly_fee->id));

        return response([
            "fee" => FeeResource::make($monthly_fee),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fee $monthly_fee)
    {
        if($monthly_fee->period != self::PERIOD) {
            return response('Not Found!', 404);
        }

        $monthly_fee->delete();

        return response([
            "message" => "success",
        ], 200);
    }

    protected function getValidatedDate($request, $id = null)
    {
        return $request->validate([
            'name' => [
                'required',
                Rule::unique(Fee::class, 'name')->ignore($id)
            ],
        ]);
    }
}
