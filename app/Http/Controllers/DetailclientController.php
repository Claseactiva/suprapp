<?php

namespace App\Http\Controllers;

use App\Models\Detailclient;
use App\Services\VehicleModelProductService;
use Illuminate\Http\Request;

class DetailclientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $detailclient = Detailclient::create($data);

        app(VehicleModelProductService::class)->syncDetailclient(
            $detailclient,
            'live',
            $request->filled('product_id') ? (int) $request->product_id : null
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Detailclient  $detailclient
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Detailclient  $detailclient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $detailclient = Detailclient::findOrFail($id);
        $detailclient->update($request->all());

        app(VehicleModelProductService::class)->syncDetailclient(
            $detailclient,
            'live',
            $request->filled('product_id') ? (int) $request->product_id : null
        );

        return;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Detailclient  $detailclient
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $detailclient = Detailclient::findOrFail($id);
        app(VehicleModelProductService::class)->removeDetailclient($detailclient->id);
        $detailclient->delete();

        return;
    }
}
