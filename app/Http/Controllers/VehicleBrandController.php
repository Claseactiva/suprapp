<?php

namespace App\Http\Controllers;

use App\Models\VehicleBrand;
use Illuminate\Http\Request;

class VehicleBrandController extends Controller
{

    /* Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
       $brands = VehicleBrand::orderBy('id', 'DESC')
                ->brand()
                ->paginate(10);

       return [
           'pagination' => [
               'total'         => $brands->total(),
               'current_page'  => $brands->currentPage(),
               'per_page'      => $brands->perPage(),
               'last_page'     => $brands->lastPage(),
               'from'          => $brands->firstItem(),
               'to'            => $brands->lastItem(),
           ],
           'vehiclebrands' => $brands
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
           'brand' => 'required|unique:vehicle_brands|min:2|max:190',
       ], [
           'brand.unique' => 'La marca ya existe y debe ser único',
           'brand.required' => 'El campo marca es obligatorio',
           'brand.min' => 'El campo marca debe tener al menos 4 caracteres',
           'brand.max' => 'El campo marca debe tener a lo más 190 caracteres'
       ]);


       $data = $request->all();
       VehicleBrand::create($data);
       
   }

   /**
    * Display the specified resource.
    *
    * @param  \App\Models\VehicleBrand  $brand
    * @return \Illuminate\Http\Response
    */
   public function show($id)
   {
       $brand = VehicleBrand::findOrFail($id);

       return $brand;
   }

   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\VehicleBrand  $brand
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, $id)
   {
    $this->validate($request, [
        'brand' => 'required|unique:vehicle_brands|min:2|max:190',
    ], [
        'brand.unique' => 'La marca ya existe y debe ser único',
        'brand.required' => 'El campo marca es obligatorio',
        'brand.min' => 'El campo nombre debe tener al menos 4 caracteres',
        'brand.max' => 'El campo nombre debe tener a lo más 190 caracteres',
    ]);

       VehicleBrand::find($id)->update($request->all());

       return;
   }

   public function all()
   {
        $all = VehicleBrand::orderBy('id', 'ASC')->paginate(10);

        return [
            'pagination_marca' => [
                'total'         => $all->total(),
                'current_page'  => $all->currentPage(),
                'per_page'      => $all->perPage(),
                'last_page'     => $all->lastPage(),
                'from'          => $all->firstItem(),
                'to'            => $all->lastItem(),
            ],
            'vehiclebrands' => $all
        ];
   }

   public function selectMarcas()
   {
        $vehiculomarcas = VehicleBrand::orderBy('id', 'ASC')->get();

        return $vehiculomarcas;
   }




   public function vbr()
    {
        $brands = VehicleBrand::select('id', 'brand')->groupBy('brand')->get();
        return $brands;
    }

}
