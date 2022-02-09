<?php

namespace App\Http\Controllers;

use App\Http\Requests\UOMRequest;
use App\Http\Resources\UOMResource;
use App\Models\UOM;
use Illuminate\Http\Request;

class UOMController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uoms = UOM::paginate(10);

        return UOMResource::collection($uoms);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UOMRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UOMRequest $request)
    {
        $uom = UOM::create($request->validated());

        return new UOMResource($uom);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UOM  $uom
     * @return \Illuminate\Http\Response
     */
    public function show(UOM $uom)
    {
        return new UOMResource($uom);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UOMRequest  $request
     * @param  \App\Models\UOM  $uom
     * @return \Illuminate\Http\Response
     */
    public function update(UOMRequest $request, UOM $uom)
    {
        $uom->update($request->validated());

        return new UOMResource($uom);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UOM  $uom
     * @return \Illuminate\Http\Response
     */
    public function destroy(UOM $uom)
    {
        $uom->delete();

        return response()->noContent();
    }
}
