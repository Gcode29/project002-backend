<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationRequest;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = Location::paginate();

        return LocationResource::collection($locations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\LocationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LocationRequest $request)
    {
        $location = Location::create($request->validated());

        return new LocationResource($location);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show(Location $location)
    {
        return new LocationResource($location);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\LocationRequest  $request
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(LocationRequest $request, Location $location)
    {
        $location->update($request->validated());

        return new LocationResource($location);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
        $location->delete();

        return response()->noContent();
    }
}
