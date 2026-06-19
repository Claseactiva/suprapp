<?php

namespace App\Http\Controllers;


use Exception;
use Intervention\Image\ImageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Vehicle;
use App\Models\CheckList;
use App\Models\CheckListCategoria;
use App\Models\CheckListIntervencion;
use App\Models\CheckListVehicle;
use App\Models\CheckListVehicleObservacion;
use App\Models\CheckListVehicleCondicion;
use App\Models\CheckListVehicleIntervencion;
use App\Models\CheckListVehicleCategoria;
use App\Models\CheckListVehicleObservacionImagen;
use Illuminate\Support\Facades\Auth;


class CheckListController extends Controller
{
    public function crearCheckList(Request $request)
    {
        $user_id = Auth::id();

        $id_checklist = CheckList::create([
            'user_id' => $user_id
        ])->id;


        $checklists = $request->checklists;

        foreach ($checklists as $checklist) {
            $id_categoria = CheckListCategoria::create([
                'check_list_id' => $id_checklist,
                'categoria' => $checklist['categoria']
            ])->id;

            foreach ($checklist['intervenciones'] as $intervencion) {
                CheckListIntervencion::create([
                    'check_list_categoria_id' => $id_categoria,
                    'intervencion' => $intervencion['intervencion'],
                ]);
            }
        }

        return $checklist;
    }

    public function mostrarFormatoCheckList()
    {
        $user_id = Auth::user()->id;

        $user_id_admin = DB::table('roles')
            ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->join('users', 'model_has_roles.model_id', '=', 'users.id')
            ->where('roles.name', '=', 'admin')
            ->select('users.id')
            ->get();

        if ($user_id_admin[0]->id == $user_id) {
            $checklists = CheckList::where('user_id', '=', $user_id_admin[0]->id)->orderby('created_at', 'desc')->first();
            $formatchecklist = CheckListCategoria::with('intervenciones')->where('check_list_id', '=', $checklists->id)->get();
            return $formatchecklist;
        } else {

            $checklists_user = CheckList::where('user_id', '=', $user_id)->orderby('created_at', 'desc')->first();

            if ($checklists_user === null) {

                $checklists_admin = CheckList::where('user_id', '=', $user_id_admin[0]->id)->orderby('created_at', 'desc')->first();
                $formatchecklistadmin = CheckListCategoria::with('intervenciones')->where('check_list_id', '=', $checklists_admin->id)->get();

                $checklist_id = CheckList::create([
                    'user_id' => $user_id,
                    'count' => count($formatchecklistadmin)
                ])->id;

                foreach ($formatchecklistadmin as $format) {
                    $categoria_id = CheckListCategoria::create([
                        'check_list_id' => $checklist_id,
                        'categoria' => $format['categoria'],
                        'count' => count($format['intervenciones'])
                    ])->id;

                    foreach ($format['intervenciones'] as $intervencion) {
                        CheckListIntervencion::create([
                            'check_list_categoria_id' => $categoria_id,
                            'intervencion' => $intervencion['intervencion'],
                        ]);
                    }
                }

                $checklists = CheckList::where('user_id', '=', $user_id)->orderby('created_at', 'desc')->first();
                $formatchecklist = CheckListCategoria::with('intervenciones')->where('check_list_id', '=', $checklists->id)->get();
                return $formatchecklist;
            } else {
                $checklists_user = CheckList::where('user_id', '=', $user_id)->orderby('created_at', 'desc')->first();


                $checklists_admin = CheckList::where('user_id', '=', $user_id_admin[0]->id)->orderby('created_at', 'desc')->first();
                $formatchecklistadmin = CheckListCategoria::with('intervenciones')->where('check_list_id', '=', $checklists_admin->id)->get();

                if (count($formatchecklistadmin) > $checklists_user->count) {
                    $diferencia = count($formatchecklistadmin) - $checklists_user->count;


                    $ultimos_registros = CheckListCategoria::with('intervenciones')->where('check_list_id', '=', $checklists_admin->id)->orderby('created_at', 'desc')->limit($diferencia)->get();

                    foreach ($ultimos_registros as $ultimo) {
                        $categoria_id = CheckListCategoria::create([
                            'check_list_id' => $checklists_user->id,
                            'categoria' => $ultimo['categoria'],
                        ])->id;
                        foreach ($ultimo['intervenciones'] as $intervencion) {
                            CheckListIntervencion::create([
                                'check_list_categoria_id' => $categoria_id,
                                'intervencion' => $intervencion['intervencion'],
                            ]);
                        }
                    }

                    CheckList::where('id', '=', $checklists_user->id)->update(['count' => count($formatchecklistadmin)]);



                    $checklists = CheckList::where('user_id', '=', $user_id)->orderby('created_at', 'desc')->first();
                    $formatchecklist = CheckListCategoria::with('intervenciones')->where('check_list_id', '=', $checklists->id)->get();
                    return $formatchecklist;
                } else {

                    $formatchecklistuser = CheckListCategoria::where('check_list_id', '=', $checklists_user->id)->get();

                    for ($a = 0; count($formatchecklistadmin) > $a; $a++) {
                        if (count($formatchecklistadmin[$a]['intervenciones']) > $formatchecklistuser[$a]->count) {
                            $diferencia = count($formatchecklistadmin[$a]['intervenciones']) - $formatchecklistuser[$a]->count;
                            $ultimos_registros = CheckListIntervencion::where('check_list_categoria_id', '=', $formatchecklistadmin[$a]->id)->orderby('created_at', 'desc')->limit($diferencia)->get();
                            foreach ($ultimos_registros as $ultimo) {
                                CheckListIntervencion::create([
                                    'check_list_categoria_id' => $formatchecklistuser[$a]->id,
                                    'intervencion' => $ultimo['intervencion'],
                                ]);
                            }
                            CheckListCategoria::where('id', '=', $formatchecklistuser[$a]->id)->update(['count' => count($formatchecklistadmin[$a]['intervenciones'])]);
                        }
                    }


                    $checklists = CheckList::where('user_id', '=', $user_id)->orderby('created_at', 'desc')->first();
                    $formatchecklist = CheckListCategoria::with('intervenciones')->where('check_list_id', '=', $checklists->id)->get();
                    return $formatchecklist;
                }
            }
        }
    }


    public function mostrarCheckList($id)
    {

        $count = CheckListVehicle::where('vehicle_id', '=', $id)->count();
        if ($count > 0) {

            $checklistvehicles = CheckListVehicle::where('vehicle_id', '=', $id)->get();
            foreach ($checklistvehicles as $checklistvehicle) {
                $formatchecklist = CheckListVehicleCategoria::with('intervenciones.observaciones.imagenes')->where('check_list_vehicle_id', '=', $checklistvehicle->id)->get();
                return $formatchecklist;
            }
        } else {

            $user_id = Auth::user()->id;
            $checklists = CheckList::where('user_id', '=', $user_id)->orderby('created_at', 'desc')->first();
            $categorias = CheckListCategoria::where('check_list_id', '=', $checklists->id)->get();

            $check_list_vehicle_id = CheckListVehicle::create([
                'check_list_id' => $checklists->id,
                'vehicle_id' => $id,
                'realizado' => 0
            ])->id;

            foreach ($categorias as $categoria) {
                $check_list_vehicle_categoria_id = CheckListVehicleCategoria::create([
                    'check_list_vehicle_id' => $check_list_vehicle_id,
                    'categoria' => $categoria->categoria
                ])->id;

                $intervenciones = CheckListIntervencion::where('check_list_categoria_id', '=', $categoria->id)->get();

                foreach ($intervenciones as $intervencion) {
                    CheckListVehicleIntervencion::create([
                        'check_list_categoria_id' => $check_list_vehicle_categoria_id,
                        'intervencion' => $intervencion->intervencion
                    ]);
                }
            }


            $checklistvehicles = CheckListVehicle::where('vehicle_id', '=', $id)->get();
            foreach ($checklistvehicles as $checklistvehicle) {
                $formatchecklist = CheckListVehicleCategoria::with('intervenciones.observaciones.imagenes')->where('check_list_vehicle_id', '=', $checklistvehicle->id)->get();
                return $formatchecklist;
            }
        }
    }

    public function editarCategoriaCheckList(Request $request)
    {

        $data = $request->all();

        CheckListCategoria::find($data['id_categoria'])->update([
            'categoria' => $data['categoria'],
        ]);
        return;
    }

    public function editarIntervencionCheckList(Request $request)
    {
        $data = $request->all();

        CheckListIntervencion::find($data['id_intervencion'])->update([
            'intervencion' => $data['intervencion'],
        ]);
        return;
    }

    public function crearCategoria(Request $request)
    {
        $user_id = Auth::user()->id;
        $checklists = CheckList::where('user_id', '=', $user_id)->orderby('created_at', 'desc')->first();

        $categorias = $request->checklists;

        for ($i = 0; $i < count($categorias); $i++) {
            $checklist_id = CheckListCategoria::create([
                'check_list_id' => $checklists->id,
                'categoria' => $categorias[$i]['categoria']
            ])->id;
        }

        $formatchecklist = CheckListCategoria::with('intervenciones')->where('check_list_id', '=', $checklists->id)->get();
        return $formatchecklist;
    }

    public function crearIntervencion(Request $request, $id)
    {

        $intervenciones = $request->intervenciones;

        for ($i = 0; $i < count($intervenciones); $i++) {
            CheckListIntervencion::create([
                'check_list_categoria_id' => $id,
                'intervencion' => $intervenciones[$i]['intervencion'],
            ]);
        }


        return $id;
    }


    function unique_multidim_array($array, $key)
    {
        $uniq = [];
        foreach ($array as $val) {
            $curVal = $val[$key]; // shortcut of the value
            $uniq[$curVal] = $val; // override previous value if exists
        }
        return array_values($uniq); // array_values to re-index array
    }


    public function guardarCheckListVehicle(Request $request)
    {
        $id_vehicle = $request->id_vehicle;
        $kilometraje = $request->kilometraje;
        $user_id = Auth::user()->id;


        $existe =  $this->unique_multidim_array($request->existe, 'id_intervencion');
        $estado =  $this->unique_multidim_array($request->estado, 'id_intervencion');


        $checklistvehicles = CheckListVehicle::where('vehicle_id', '=', $id_vehicle)->get();
        foreach ($checklistvehicles as $checklistvehicle) {

            $categorias = CheckListVehicleCategoria::where('check_list_vehicle_id', '=', $checklistvehicle->id)->get();
            foreach ($categorias as $categoria) {

                $intervenciones = CheckListVehicleIntervencion::where('check_list_categoria_id', '=', $categoria->id)->get();
                foreach ($intervenciones as $intervencion) {

                    $count_condicion = CheckListVehicleCondicion::where('check_list_intervencion_id', '=', $intervencion->id)->count();
                    if ($count_condicion > 0) {

                        for ($i = 0; $i < count($existe); $i++) {

                            if ($existe[$i]['existe'] == 'no') {
                                CheckListVehicleCondicion::where('check_list_intervencion_id', '=', $existe[$i]['id_intervencion'])
                                    ->update([
                                        'estado' => '',
                                        'existe' => $existe[$i]['existe']
                                    ]);
                            } else {

                                for ($y = 0; $y < count($estado); $y++) {

                                    if ($existe[$i]['id_intervencion'] == $estado[$y]['id_intervencion']) {
                                        CheckListVehicleCondicion::where('check_list_intervencion_id', '=', $existe[$i]['id_intervencion'])
                                            ->update([
                                                'estado' => $estado[$y]['estado'],
                                                'existe' => $existe[$i]['existe']
                                            ]);
                                    }
                                }
                            }
                        }

                        Vehicle::find($id_vehicle)->update([
                            'km' => $kilometraje,
                        ]);

                        CheckListVehicle::find($checklistvehicle->id)->update([
                            'realizado' => 1
                        ]);
                    } else {
                        for ($i = 0; $i < count($existe); $i++) {

                            if ($existe[$i]['existe'] == 'no') {
                                CheckListVehicleCondicion::create([
                                    'check_list_intervencion_id' => $existe[$i]['id_intervencion'],
                                    'estado' => '',
                                    'existe' => $existe[$i]['existe']
                                ]);
                            } else {

                                for ($y = 0; $y < count($estado); $y++) {

                                    if ($existe[$i]['id_intervencion'] == $estado[$y]['id_intervencion']) {
                                        CheckListVehicleCondicion::create([
                                            'check_list_intervencion_id' => $existe[$i]['id_intervencion'],
                                            'estado' => $estado[$y]['estado'],
                                            'existe' => $existe[$i]['existe']
                                        ]);
                                    }
                                }
                            }
                        }

                        Vehicle::find($id_vehicle)->update([
                            'km' => $kilometraje,
                        ]);

                        CheckListVehicle::find($checklistvehicle->id)->update([
                            'realizado' => 1
                        ]);
                    }
                }
            }
        }
    }



    public function agregarObservacionCheckList(Request $request)
    {
        $arreglo = array();

        $id_intervencion =  $request->id_intervencion_checklist;
        $imagenes = $request->imagenes_checklist;

        $manager = new ImageManager(array('local' => 'imagick'));

        $checkListVehicleObservacionId = CheckListVehicleObservacion::create([
            'check_list_intervencion_id' => $id_intervencion,
            'observacion' => $request->observacion_checklist
        ])->id;

        if (!empty($imagenes)) {

            foreach ($imagenes as $imagen) {

                $filename = uniqid() . '.' . $imagen->getClientOriginalExtension();
                $path = storage_path('app/public/images/checklist/' . $filename);

                $img = $manager->make($imagen->getRealPath());
                $img->resize(1000, 1000, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path);

                $url = 'app/public/images/checklist/' . $filename;

                CheckListVehicleObservacionImagen::create([
                    'check_list_vehicle_observaciones_id' => $checkListVehicleObservacionId,
                    'imagen' => $url
                ]);

                array_push($arreglo, $path);
            }
        }
        return response($arreglo);
    }


    public function checklistvehicles()
    {

        $user_id = Auth::user()->id;

        $roles = DB::table('roles')
            ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->join('users', 'model_has_roles.model_id', '=', 'users.id')
            ->where('users.id', '=', $user_id)
            ->select('roles.name')
            ->get();

        if ($roles[0]->name == 'admin') {
            $checklistvehicles = Vehicle::with('checklist')->get();
            foreach ($checklistvehicles as $checklistvehicle) {
                if ($checklistvehicle->checklist->count() > 0) {
                    $checklistvehicles = Vehicle::with('checklist')->where('id', '=', $checklistvehicle->id)->get();
                    return $checklistvehicles;
                }
            }
        } else {
            $checklistvehicles = Vehicle::with('checklist')->where('user_id', '=', $user_id)->get();
            foreach ($checklistvehicles as $checklistvehicle) {
                if ($checklistvehicle->checklist->count() > 0) {
                    $checklistvehicles = Vehicle::with('checklist')->where('id', '=', $checklistvehicle->id)->get();
                    return $checklistvehicles;
                }
            }
        }
    }


    public function mostrarCheckListVehicles($id)
    {

        $checklistvehicles = CheckListVehicle::where('id', '=', $id)->get();
        foreach ($checklistvehicles as $checklistvehicle) {

            $formatchecklist = CheckListVehicleCategoria::with(['intervenciones.condiciones', 'intervenciones.observaciones.imagenes'])->where('check_list_vehicle_id', '=', $checklistvehicle->id)->get();
            $array = [
                $formatchecklist,
                'id_checklist_vehicle' => $id
            ];

            return $array;
        }
    }



    public function mostrarCondiciones(Request $request)
    {

        $intervenciones = CheckListVehicleIntervencion::with('condiciones', 'observaciones')->where('check_list_categoria_id', '=', $request->id_categoria)->get();
        return $intervenciones;
    }

    public function roleschecklists()
    {

        $user_id = Auth::user()->id;

        $roles = DB::table('roles')
            ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->join('users', 'model_has_roles.model_id', '=', 'users.id')
            ->where('users.id', '=', $user_id)
            ->select('roles.name')
            ->get();

        return $roles[0]->name;
    }


    public function eliminarImagenChecklist(Request $request)
    {
        if (file_exists(storage_path($request->imagen))) {
            // Eliminar la imagen
            unlink(storage_path($request->imagen));
        }

        // Buscar y eliminar la entrada en la base de datos
        $imagen = CheckListVehicleObservacionImagen::find($request->id);
        if ($imagen) {
            $imagen->delete();
            return response()->json(['message' => 'Imagen eliminada correctamente'], 200);
        } else {
            // Si la entrada no existe en la base de datos, puedes manejar este caso según tus necesidades
            return response()->json(['message' => 'La entrada de la base de datos no existe'], 404);
        }
    }

    public function eliminarObservacionChecklist(Request $request)
    {

        $observaciones = CheckListVehicleObservacion::with('imagenes')->where('id', '=', $request->id)->get();
        foreach ($observaciones[0]->imagenes as $imagen) {
            if (file_exists(storage_path($imagen->imagen))) {
                // Eliminar la imagen
                unlink(storage_path($imagen->imagen));
            }
        }

        CheckListVehicleObservacion::find($request->id)->delete();
    }


    public function eliminarCategoriaChecklist($id)
    {
        try {
            CheckListCategoria::find($id)->delete();
            return response()->json(['message' => 'La categoria se elimino corretamente'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e], 200);
        }
    }

    public function eliminarIntervencionChecklist($id)
    {
        try {
            CheckListIntervencion::find($id)->delete();
            return response()->json(['message' => 'La intervencion se elimino corretamente'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e], 200);
        }
    }
}
