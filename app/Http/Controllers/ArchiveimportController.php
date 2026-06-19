<?php

namespace App\Http\Controllers;

use App\Models\Archiveimport;
use Illuminate\Http\Request;

class ArchiveimportController extends Controller
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
        $arreglo = array();
        $uploadedFile = $request->pics;
        $id =  $request->id;

        foreach ($uploadedFile as $file) {
            $archivo = $file->store('dummy');
            $url = env('APP_URL').':8000/'.$archivo;

            Archiveimport::create(['import_id' => $id, 'url'=> $url]);
            array_push($arreglo, $url);
        }

        //return response([ 'status' => 'success' ], 200);

        return response($arreglo);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Archiveimport  $archiveimport
     * @return \Illuminate\Http\Response
     */
    public function show(Archiveimport $archiveimport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Archiveimport  $archiveimport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Archiveimport $archiveimport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Archiveimport  $archiveimport
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $image = Archiveimport::findOrFail($id);

        //$filename = str_replace("http://comercialsupra.cl/registro/dummy/", "", $image->url);

        $filename = str_replace("http://localhost:8000/dummy/", "", $image->url);


        // unlink(public_path() .'/dummy/'. $filename );
        unlink(getcwd() .'/dummy/'. $filename );

        $image->delete();

        return;
    }
}
