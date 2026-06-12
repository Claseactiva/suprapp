<?php

namespace App\Http\Controllers;

use App\Models\Detailclient;
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

        Detailclient::create($data);
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
        Detailclient::find($id)->update($request->all());

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
        $detailclient->delete();

        return;
    }
}
