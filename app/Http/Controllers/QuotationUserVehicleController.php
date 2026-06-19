<?php

namespace App\Http\Controllers;

use App\Models\QuotationUserVehicle;
use Illuminate\Http\Request;

class QuotationUserVehicleController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\QuotationUserVehicle  $quotationUserVehicle
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return QuotationUserVehicle::findOrFail($id)->patentchasis;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\QuotationUserVehicle  $quotationUserVehicle
     * @return \Illuminate\Http\Response
     */
    public function edit(QuotationUserVehicle $quotationUserVehicle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\QuotationUserVehicle  $quotationUserVehicle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, QuotationUserVehicle $quotationUserVehicle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QuotationUserVehicle  $quotationUserVehicle
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuotationUserVehicle $quotationUserVehicle)
    {
        //
    }
}
