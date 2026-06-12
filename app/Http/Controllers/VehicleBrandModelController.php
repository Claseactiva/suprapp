<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleBrandModel;
use App\Models\VehicleBrand;
use App\Models\VehicleModel;

class VehicleBrandModelController extends Controller
{

    public $timestamps = false;

    public function index()
    {
        $models = VehicleBrandModel::select('id','model')->get();

        return $models;
    }

    public function brands()
    {
        $brands = VehicleBrand::select('id', 'brand')->groupBy('brand')->get();
        return $brands;
    }

    public function models($brand)
    {
        $models = VehicleModel::select('id','model')->where('brand_id', '=', $brand)->get();

        return $models;
    }

    public function show($id)
    {
        $brand = VehicleBrandModel::findOrFail($id);
        return $brand;
    }

    public function all(){
        $all = VehicleBrandModel::orderBy('id', 'ASC')->paginate(10);

        return [
            'pagination' => [
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

    public function store(Request $request){

        $this->validate($request, [
            'brand' => 'required|min:2|max:190',
        ], [
            'brand.required' => 'El campo nombre es obligatorio',
            'brand.min' => 'El campo nombre debe tener al menos 4 caracteres',
            'brand.max' => 'El campo nombre debe tener a lo más 190 caracteres',
        ]);
 
        $data = $request->all();
 
        VehicleBrandModel::create($data);

    }
}
