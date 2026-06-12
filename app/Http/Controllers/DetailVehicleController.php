<?php

namespace App\Http\Controllers;

use App\Models\DetailVehicle;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use App\Models\Image;

class DetailVehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $detalle = DetailVehicle::orderBy('id', 'DESC')->get();

        return $detalle;
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
        $pics = $request->pics;

        $manager = new ImageManager(array('local' => 'imagick'));

        Vehicle::where('id', $data['vehicle_id'])->update(['km' => $data['km']]);
        $detail = DetailVehicle::create($data);


        if (!empty($pics)) {

            foreach ($pics as $pic) {

                $filename = uniqid() . '.' . $pic->getClientOriginalExtension();
                $path = storage_path('app/public/images/vehicles/' . $filename);


                $img = $manager->make($pic->getRealPath());
                $img->resize(1000, 1000, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path);

                $url = 'app/public/images/vehicles/' . $filename;

                Image::create(['detail_vehicle_id' => $detail->id, 'url' => $url]);
            }
        }

        return $detail->id;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DetailVehicle  $detailVehicle
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detalle = DetailVehicle::find($id)->images;

        return $detalle;
    }
}
