<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseOrderDetailImage;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;

class PurchaseOrderDetailController extends Controller
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
        return PurchaseOrderDetail::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseOrderDetail  $purchaseOrderDetail
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
     * @param  \App\Models\PurchaseOrderDetail  $purchaseOrderDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $detail = PurchaseOrderDetail::findOrFail($id);
        $detail->update($request->all());

        return;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseOrderDetail  $purchaseOrderDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PurchaseOrderDetail::findOrFail($id)->delete();

        return;
    }

    public function uploadImages(Request $request)
    {
        $manager = new ImageManager(array('local' => 'imagick'));
        $imagenes = $request->file('images', []);
        $arreglo = [];

        foreach ($imagenes as $imagen) {
            $filename = uniqid() . '.' . $imagen->getClientOriginalExtension();
            $path = public_path('storage/images/purchase_order_details/' . $filename);

            $manager->make($imagen->getRealPath())->resize(1000, 1000, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path);

            $arreglo[] = PurchaseOrderDetailImage::create([
                'purchase_order_detail_id' => $request->purchase_order_detail_id,
                'imagen' => 'storage/images/purchase_order_details/' . $filename,
            ]);
        }

        return response($arreglo);
    }

    public function deleteImage($id)
    {
        $image = PurchaseOrderDetailImage::findOrFail($id);
        $absolutePath = public_path($image->imagen);

        if (file_exists($absolutePath)) {
            unlink($absolutePath);
        }

        $image->delete();

        return response()->json(['message' => 'Imagen eliminada correctamente']);
    }
}
