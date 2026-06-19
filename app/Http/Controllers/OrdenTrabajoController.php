<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use App\Models\OrdenTrabajo;
use App\Models\Trabajos;
use App\Models\ImageOrdenTrabajo;
use App\Models\Observacion;
use App\Models\ObservacionImagen;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;


class OrdenTrabajoController extends Controller
{

    public function index()
    {
        $user_id = Auth::id();

        $ordenestrabajos = OrdenTrabajo::with('vehicle', 'trabajo')->where('user_id', '=', $user_id)->get();
        return $ordenestrabajos;
    }

    public function obtenerFotosOrdenTrabajo($id)
    {
        $fotosordentrabajo = ImageOrdenTrabajo::with('trabajo')->where('trabajo_id', '=', $id)->get();
        return $fotosordentrabajo;
    }

    public function observaciones($id)
    {
        $observacion = Observacion::with('imagenes')->where('trabajo_id', '=', $id)->get();
        return $observacion;
    }

    public function trabajos($id)
    {

        $ordenestrabajos = OrdenTrabajo::where('vehicle_id', '=', $id)->get();
        foreach ($ordenestrabajos as $ordentrabajo) {

            $trabajos = Trabajos::with('imagenes', 'observaciones.imagenes')->where('orden_trabajo_id', '=', $ordentrabajo->id)->get();
            return $trabajos;
        }
    }


    public function store(Request $request)
    {
        $data = $request->all();
        $user_id = Auth::id();

        // Obtener todas las órdenes de trabajo para el vehículo dado
        $ordenestrabajos = OrdenTrabajo::where('vehicle_id', '=', $data['vehicle_id'])->get();

        if ($ordenestrabajos->isNotEmpty()) {
            // Si existe al menos una orden de trabajo, usar la primera encontrada
            $orden_trabajo_id = $ordenestrabajos->first()->id;
        } else {
            // Si no existe ninguna orden de trabajo, crear una nueva
            $orden_trabajo_id = OrdenTrabajo::create([
                'user_id' => $user_id,
                'vehicle_id' => $data['vehicle_id'],
                'km' => $data['km']
            ])->id;
        }

        // Crear un nuevo trabajo
        Trabajos::create([
            'orden_trabajo_id' => $orden_trabajo_id,
            'descripcion' => $data['descripcion'],
            'realizado' => 0,
            'km' => $data['km']
        ]);

        // Actualizar el kilometraje del vehículo
        Vehicle::find($data['vehicle_id'])->update([
            'km' => $data['km'],
        ]);

        // Actualizar el kilometraje de la orden de trabajo
        OrdenTrabajo::where('vehicle_id', '=', $data['vehicle_id'])->update([
            'km' => $data['km']
        ]);

        // Obtener todos los trabajos para la orden de trabajo dada
        $trabajos = Trabajos::where('orden_trabajo_id', '=', $orden_trabajo_id)->get();
        $ultimokm = $this->ultimoKilometraje($trabajos);

        $response = [
            'trabajos' => $trabajos,
            'ultimo_km' => $ultimokm
        ];

        return response()->json($response);
    }


    public function EliminarOrdentrabajo(Request $request)
    {
        $ultimokm = 0;
        $observaciones = Observacion::where('trabajo_id', '=', $request->id)->get();

        foreach ($observaciones as $observacion) {
            $imagenes = ObservacionImagen::where('observaciones_id', '=', $observacion->id)->get();
            foreach ($imagenes as $imagen) {
                unlink(storage_path($imagen->imagen));
            }
            Observacion::findOrFail($observacion->id)->delete();
        }

        $ordenes_trabajos = ImageOrdenTrabajo::where('trabajo_id', '=', $request->id)->get();
        foreach ($ordenes_trabajos as $orden_trabajo) {
            ImageOrdenTrabajo::findOrFail($orden_trabajo->id)->delete();
            unlink(storage_path($orden_trabajo->url));
        }


        $trabajo = Trabajos::findOrFail($request->id);
        $trabajo->delete();

        $trabajos = Trabajos::where('orden_trabajo_id', '=', $trabajo->orden_trabajo_id)->get();
        $ultimokm_trabajos = $this->ultimoKilometraje($trabajos);

        $vehicle = Vehicle::find($request->vehicle_id);

        if($vehicle->km < $ultimokm_trabajos){ //10002 < 10001
            $vehicle->update([
                'km' => $ultimokm_trabajos,
            ]);

            OrdenTrabajo::where('vehicle_id', '=', $request->vehicle_id)->update([
                'km' => $ultimokm_trabajos
            ]);
            $ultimokm = $ultimokm_trabajos;
        }else{
            $ultimokm = $vehicle->km;
        }
        
        $response = [
            'trabajos' => $trabajos,
            'ultimo_km' => $ultimokm
        ];

        return response()->json($response);
    }


    public function checkRealizado(Request $request)
    {
        $data = $request->all();

        Trabajos::where('id', $data['check'])->update([
            'realizado' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function NocheckRealizado(Request $request)
    {
        $data = $request->all();

        Trabajos::where('id', $data['check'])->update([
            'realizado' => 0
        ]);
    }


    public function SubirFotosOrdenTrabajo(Request $request)
    {
        $arreglo = array();
        $uploadedFile = $request->pics;
        $id =  $request->id;

        $manager = new ImageManager(array('local' => 'imagick'));

        foreach ($uploadedFile as $file) {

            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $path = storage_path('app/public/images/orden_trabajos/' . $filename);


            $img = $manager->make($file->getRealPath());
            $img->resize(1000, 1000, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path);

            $url = 'app/public/images/orden_trabajos/' . $filename;

            ImageOrdenTrabajo::create(['trabajo_id' => $id, 'url' => $url]);

            array_push($arreglo, $path);
        }
        return response($arreglo);
    }


    public function AgregarObservacion(Request $request)
    {
        $arreglo = array();
        $imagenes = $request->imagenes_observacion;

        $manager = new ImageManager(array('local' => 'imagick'));

        $observacion_id = Observacion::create([
            'trabajo_id' => $request->id,
            'observacion' => $request->observacion,
        ])->id;

        if (!empty($imagenes)) {

            foreach ($imagenes as $imagen) {

                $filename = uniqid() . '.' . $imagen->getClientOriginalExtension();
                $path = storage_path('app/public/images/observaciones/' . $filename);

                $img = $manager->make($imagen->getRealPath());
                $img->resize(1000, 1000, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path);

                $url = 'app/public/images/observaciones/' . $filename;

                ObservacionImagen::create([
                    'observaciones_id' => $observacion_id,
                    'imagen' => $url,
                ]);

                array_push($arreglo, $url);
            }
        }
        return response($arreglo);
    }


    public function EliminarObservacion(Request $request)
    {

        $imagen = ObservacionImagen::where('id', '=', $request->id)->first();
        if (file_exists(storage_path($imagen->imagen))) {
            unlink(storage_path($imagen->imagen));
        }

        ObservacionImagen::find($request->id)->delete();
        return response()->json(['id' => $imagen->observaciones_id], 200);
    }


    public function EliminarImagenObservacion(Request $request)
    {
        $imagen = ImageOrdenTrabajo::where('id', '=', $request->id)->first();
        if (file_exists(storage_path($imagen->url))) {
            unlink(storage_path($imagen->url));
        }

        ImageOrdenTrabajo::find($request->id)->delete();
        return response()->json(['id' => $imagen->trabajo_id], 200);
    }


    public function EliminarEditarObservacion(Request $request)
    {

        $observaciones = Observacion::with('imagenes')->where('id', '=', $request->id)->get();
        foreach ($observaciones[0]->imagenes as $imagen) {
            if (file_exists(storage_path($imagen->imagen))) {
                // Eliminar la imagen
                unlink(storage_path($imagen->imagen));
            }
        }

        Observacion::find($request->id)->delete();
    }


    public function ActualizarOrdentrabajo(Request $request)
    {
        $data = $request->all();

        $trabajo = Trabajos::find($data['vehicle_id']);

        $trabajo->update([
            'descripcion' => $data['descripcion'],
            'km' => $data['km']
        ]);

        $ordenTrabajo = OrdenTrabajo::find($trabajo->orden_trabajo_id);

        $ordenTrabajo->update([
            'km' => $data['km']
        ]);

        Vehicle::find($ordenTrabajo->vehicle_id)->update([
            'km' => $data['km'],
        ]);

        $trabajos = Trabajos::where('orden_trabajo_id', '=', $trabajo->orden_trabajo_id)->get();
        $ultimokm = $this->ultimoKilometraje($trabajos);

        $response = [
            'trabajos' => $trabajos,
            'ultimo_km' => $ultimokm,
            'vehicle_id' => $ordenTrabajo->vehicle_id
        ];

        return response()->json($response);
    }

    public function ultimoKilometraje($data)
    {
        // Convertir la colección de Eloquent a un array
        $dataArray = $data->toArray();

        // Comprobar si el array está vacío
        if (empty($dataArray)) {
            return 0;  // O cualquier valor predeterminado que consideres adecuado
        }

        // Ordenar el array por created_at
        usort($dataArray, function ($a, $b) {
            return strtotime($a['created_at']) - strtotime($b['created_at']);
        });

        // Obtener el último elemento del array
        $ultimoElemento = end($dataArray);

        // Asegurarse de que el último elemento sea un array antes de intentar acceder a 'km'
        if ($ultimoElemento && is_array($ultimoElemento)) {
            return $ultimoElemento['km'];
        }

        return 0;  // O cualquier valor predeterminado que consideres adecuado
    }
}
