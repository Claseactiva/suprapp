<?php

namespace App\Http\Controllers;

use App\Models\VehicleEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleEngineController extends Controller
{

    public function all($year){
        $engines = VehicleEngine::select(DB::raw('vehicle_engines.id as id, vehicle_engines.v_engine as engine'))
                                    ->join('vehicle_years', 'vehicle_years.id', '=', 'vehicle_engines.year_id')
                                    ->where('vehicle_years.id', '=', $year)->get();
        return $engines;
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
           'v_engine' => 'required|min:4|max:190'
       ], [
           'v_engine.required' => 'El campo motor es obligatorio',
           'v_engine.min' => 'El campo motor debe tener al menos 4 caracteres',
           'v_engine.max' => 'El campo motor debe tener a lo más 4 caracteres'
       ]);

       $engines = DB::table('vehicle_engines')->where([
                                                    ['year_id', '=', $request->year_id],
                                                    ['v_engine', '=', $request->v_engine]])
                                                    ->get();
        if (!$engines->isEmpty()) {
            return response()->json([
                'errors' => [
                    'v_engine' => 'El año y motor, ya existen'
                    ]
                ], 422);
        }else{
            $data = $request->all();
            VehicleEngine::create($data);
        }
   }

   public function update(Request $request, $id)
   {
        $this->validate($request, [
            'v_engine' => 'required|min:4|max:190'
        ], [
            'v_engine.required' => 'El campo motor de inicio es obligatorio',
            'v_engine.min' => 'El campo motor de inicio debe tener al menos 4 caracteres',
            'v_engine.max' => 'El campo motor de inicio debe tener a lo más 4 caracteres'
        ]);

        VehicleEngine::find($id)->update($request->all());

       return;
   }

   public function all_motors()
   {
       
        $motors = VehicleEngine::select(DB::raw('vehicle_engines.id as id,
                                              vehicle_years.v_year as year,
                                              vehicle_engines.v_engine as motor,
                                              vehicle_models.model as model'))
                ->join('vehicle_years', 'vehicle_years.id', '=', 'vehicle_engines.year_id')
                ->join('vehicle_models', 'vehicle_models.id', '=', 'vehicle_years.v_id')
                ->orderBy('id', 'DESC')
                ->paginate((int) request('per_page', 20));

       return [
           'pagination_motor' => [
               'total'         => $motors->total(),
               'current_page'  => $motors->currentPage(),
               'per_page'      => $motors->perPage(),
               'last_page'     => $motors->lastPage(),
               'from'          => $motors->firstItem(),
               'to'            => $motors->lastItem(),
           ],
           'vehiclemotors' => $motors
       ];

   }

   public function selectMotores()
   {
        $motores = VehicleEngine::orderBy('id', 'ASC')->get();

        return $motores;
   }

}
