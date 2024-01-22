<?php

namespace App\Http\Controllers;

use App\Http\Resources\FeeCollection;
use App\Http\Resources\FeeResource;
use App\Models\Fee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        FeeCollection::wrap('fees');

        return FeeCollection::make(Fee::paginate(request()->per_page));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $this->getValidatedDate($request);

        $fee = Fee::create($this->getValidatedDate($request));

        return response([
            "fee" => FeeResource::make($fee),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Fee $fee)
    {
        return response([
            "fee" => FeeResource::make($fee),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fee $fee)
    {
        // return $this->getValidatedDate($request, $fee->id);

        $fee->update($this->getValidatedDate($request, $fee->id));

        return response([
            "fee" => FeeResource::make($fee),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fee $fee)
    {
        $fee->delete();

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
            'period' => [
                'required',
                'numeric',
            ]
        ]);
    }
}
