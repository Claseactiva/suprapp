<?php

namespace App\Http\Controllers;

use App\Models\Detailclient;
use App\Models\DetailclientImage;
use App\Services\VehicleModelProductService;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;

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

    public function uploadImages(Request $request)
    {
        $manager = new ImageManager(array('local' => 'imagick'));
        $imagenes = $request->file('images', []);
        $arreglo = [];

        foreach ($imagenes as $imagen) {
            $filename = uniqid() . '.' . $imagen->getClientOriginalExtension();
            $path = public_path('storage/images/detailclients/' . $filename);

            $manager->make($imagen->getRealPath())->resize(1000, 1000, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path);

            $arreglo[] = DetailclientImage::create([
                'detailclient_id' => $request->detailclient_id,
                'imagen' => 'storage/images/detailclients/' . $filename,
            ]);
        }

        return response($arreglo);
    }

    public function deleteImage($id)
    {
        $image = DetailclientImage::findOrFail($id);
        $absolutePath = public_path($image->imagen);

        if (file_exists($absolutePath)) {
            unlink($absolutePath);
        }

        $image->delete();

        return response()->json(['message' => 'Imagen eliminada correctamente']);
    }
}
