<?php

namespace App\Http\Controllers;

use App\Http\Resources\FeeCollection;
use App\Http\Resources\FeeResource;
use App\Models\Fee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnnualFeeController extends Controller
{
    const PERIOD = 2;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        FeeCollection::wrap('fees');

        return FeeCollection::make(Fee::period(self::PERIOD)->paginate(request()->per_page));
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
    public function show(Fee $annual_fee)
    {
        if($annual_fee->period != self::PERIOD) {
            return response('Not Found!', 404);
        }

        return response([
            "fee" => FeeResource::make($annual_fee),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fee $annual_fee)
    {
        if($annual_fee->period != self::PERIOD) {
            return response('Not Found!', 404);
        }

        // return $this->getValidatedDate($request, $fee->id);

        $annual_fee->update($this->getValidatedDate($request, $annual_fee->id));

        return response([
            "fee" => FeeResource::make($annual_fee),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fee $annual_fee)
    {
        if($annual_fee->period != self::PERIOD) {
            return response('Not Found!', 404);
        }

        $annual_fee->delete();

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
