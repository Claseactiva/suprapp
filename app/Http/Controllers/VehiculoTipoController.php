<?php

namespace App\Http\Controllers;

use App\Models\TipoVehiculo;
use Illuminate\Http\Request;

class VehiculoTipoController extends Controller
{
    /* Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
       $tipos = TipoVehiculo::orderBy('id', 'DESC')
                ->tipo_vehiculo()
                ->paginate(10);

       return [
           'pagination' => [
               'total'         => $tipos->total(),
               'current_page'  => $tipos->currentPage(),
               'per_page'      => $tipos->perPage(),
               'last_page'     => $tipos->lastPage(),
               'from'          => $tipos->firstItem(),
               'to'            => $tipos->lastItem(),
           ],
           'vehiculotipos' => $tipos
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
        'tipo_vehiculo' => 'required|unique:vehicle_tipos|min:2|max:190'
       ], [
           'tipo_vehiculo.unique' => 'El tipo de vehiculo ya existe y debe ser único',
           'tipo_vehiculo.required' => 'El campo nombre es obligatorio',
           'tipo_vehiculo.min' => 'El campo nombre debe tener al menos 4 caracteres',
           'tipo_vehiculo.max' => 'El campo nombre debe tener a lo más 190 caracteres',
       ]);

       $data = $request->all();

       TipoVehiculo::create($data);
   }

   /**
    * Display the specified resource.
    *
    * @param  \App\Models\TipoVehiculo  $brand
    * @return \Illuminate\Http\Response
    */
   public function show($id)
   {
       $tipo = TipoVehiculo::findOrFail($id);

       return $tipo;
   }

   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\TipoVehiculo  $brand
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, $id)
   {
    $this->validate($request, [
        'tipo_vehiculo' => 'required|unique:vehicle_tipos|min:2|max:190',
    ], [
        'tipo_vehiculo.unique' => 'El tipo de vehiculo ya existe y debe ser único',
        'tipo_vehiculo.required' => 'El campo marca es obligatorio',
        'tipo_vehiculo.min' => 'El campo nombre debe tener al menos 4 caracteres',
        'tipo_vehiculo.max' => 'El campo nombre debe tener a lo más 190 caracteres'
    ]);

    TipoVehiculo::find($id)->update($request->all());

       return;
   }

   public function all()
   {
        $all = TipoVehiculo::orderBy('id', 'ASC')->paginate(10);

        return [
            'pagination_tipo' => [
                'total'         => $all->total(),
                'current_page'  => $all->currentPage(),
                'per_page'      => $all->perPage(),
                'last_page'     => $all->lastPage(),
                'from'          => $all->firstItem(),
                'to'            => $all->lastItem(),
            ],
            'vehiculotipos' => $all
        ];
   }
   public function selectTipos()
   {
        $vehiculotipos = TipoVehiculo::orderBy('id', 'ASC')->get();

        return $vehiculotipos;
   }
}
