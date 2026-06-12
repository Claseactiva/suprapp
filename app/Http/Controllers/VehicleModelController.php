<?php

namespace App\Http\Controllers;

use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleModelController extends Controller
{

    /* Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
       
        $models = VehicleModel::select(DB::raw('vehicle_models.id as id,
                                                vehicle_brands.brand as brand,
                                                vehicle_tipos.tipo_vehiculo as tipo,
                                                vehicle_models.model'))
                ->join('vehicle_brands', 'vehicle_brands.id', '=', 'vehicle_models.brand_id')
                ->join('vehicle_tipos', 'vehicle_tipos.id', '=', 'vehicle_models.tipo_id')
                ->paginate(10);

       return [
           'pagination_modelo' => [
               'total'         => $models->total(),
               'current_page'  => $models->currentPage(),
               'per_page'      => $models->perPage(),
               'last_page'     => $models->lastPage(),
               'from'          => $models->firstItem(),
               'to'            => $models->lastItem(),
           ],
           'vehiclemodels' => $models
       ];

   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
       $this->validate($request, [
           //'brand_id' => 'required',
           'model' => 'required|unique:vehicle_models|min:2|max:190',
       ], [
           //'brand_id.required' => 'El campo brand_id es obligatorio',
           'model.unique' => 'El modelo ya existe y debe ser único',
           'model.required' => 'El campo modelo es obligatorio',
           'model.min' => 'El campo nombre debe tener al menos 4 caracteres',
           'model.max' => 'El campo nombre debe tener a lo más 190 caracteres'
       ]);

       $data = $request->all();
       
       VehicleModel::create($data);
       
  }

   /**
    * Display the specified resource.
    *
    * @param  \App\Models\VehicleModel  $model
    * @return \Illuminate\Http\Response
    */
   public function show($id)
   {
       $model = VehicleModel::findOrFail($id);

       return $model;
   }

   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\VehicleModel  $model
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, $id)
   {
    $this->validate($request, [
        'model' => 'required|min:2|max:190',
    ], [
        'model.required' => 'El campo nombre es obligatorio',
        'model.min' => 'El campo nombre debe tener al menos 4 caracteres',
        'model.max' => 'El campo nombre debe tener a lo más 190 caracteres',
    ]);

       VehicleModel::find($id)->update($request->all());

       return;
   }


   public function all()
   {

        $vehiclemodels = VehicleModel::select(DB::raw('vehicle_models.id as id,vehicle_brands.brand as brand,vehicle_models.model'))
        ->join('vehicle_brands', 'vehicle_brands.id', '=', 'vehicle_models.brand_id')
        ->brand()
        ->get();
        
        return $vehiclemodels;
   }


   public function vmr($brand)
    {
        $models = VehicleModel::select('id','model')->where('brand_id', '=', $brand)->get();

        return $models;
    }

    public function mm()
    {
        $models = VehicleModel::select('id', 'model')->groupBy('model')->get();
        return $models;
    }

}