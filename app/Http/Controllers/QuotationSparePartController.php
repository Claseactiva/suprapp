<?php

namespace App\Http\Controllers;

use App\Models\QuotationSparePart;
use App\Models\QuotationSparePartImage;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;

class QuotationSparePartController extends Controller
{
    public function index($quotationclientId)
    {
        return QuotationSparePart::with('images')
            ->where('quotationclient_id', $quotationclientId)
            ->orderBy('id', 'DESC')
            ->get();
    }

    public function store(Request $request)
    {
        return QuotationSparePart::create($request->only([
            'quotationclient_id', 'product_id', 'product', 'detail', 'quantity'
        ]));
    }

    public function update(Request $request, $id)
    {
        $sparePart = QuotationSparePart::findOrFail($id);
        $sparePart->update($request->only([
            'product_id', 'product', 'detail', 'quantity'
        ]));

        return $sparePart;
    }

    public function destroy($id)
    {
        QuotationSparePart::findOrFail($id)->delete();

        return;
    }

    public function uploadImages(Request $request)
    {
        $manager = new ImageManager(array('local' => 'imagick'));
        $imagenes = $request->file('images', []);
        $arreglo = [];

        foreach ($imagenes as $imagen) {
            $filename = uniqid() . '.' . $imagen->getClientOriginalExtension();
            $path = public_path('storage/images/quotation-spare-parts/' . $filename);

            $manager->make($imagen->getRealPath())->resize(1000, 1000, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path);

            $arreglo[] = QuotationSparePartImage::create([
                'quotation_spare_part_id' => $request->quotation_spare_part_id,
                'imagen' => 'storage/images/quotation-spare-parts/' . $filename,
            ]);
        }

        return response($arreglo);
    }

    public function deleteImage($id)
    {
        $image = QuotationSparePartImage::findOrFail($id);
        $absolutePath = public_path($image->imagen);

        if (file_exists($absolutePath)) {
            unlink($absolutePath);
        }

        $image->delete();

        return response()->json(['message' => 'Imagen eliminada correctamente']);
    }
}
