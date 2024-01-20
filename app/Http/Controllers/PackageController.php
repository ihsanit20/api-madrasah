<?php

namespace App\Http\Controllers;

use App\Http\Resources\PackageCollection;
use App\Http\Resources\PackageResource;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        PackageCollection::wrap('packages');

        return PackageCollection::make(Package::paginate(request()->per_page));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $this->getValidatedDate($request);

        $package = Package::create($this->getValidatedDate($request));

        return response([
            "package" => PackageResource::make($package),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Package $package)
    {
        return response([
            "package" => PackageResource::make($package),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package)
    {
        // return $this->getValidatedDate($request, $package->id);

        $package->update($this->getValidatedDate($request, $package->id));

        return response([
            "package" => PackageResource::make($package),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        $package->delete();

        return response([
            "message" => "success",
        ], 200);
    }

    protected function getValidatedDate($request, $id = null)
    {
        return $request->validate([
            'name' => [
                'required',
                Rule::unique(Package::class, 'name')->ignore($id)
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'is_active' => [
                'sometimes',
                'required',
                'boolean',
            ]
        ]);
    }
}
