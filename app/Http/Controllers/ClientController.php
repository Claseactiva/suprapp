<?php

namespace App\Http\Controllers;


use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $idUser = Auth::id();
        $clients = Client::whereHas('user', function ($query) use ($idUser) {
            $query->where('id', '=', $idUser)
                ->where('clients.type', '<>', 'Cliente Particular');
        })->orderBy('id', 'DESC')
            ->with('activities')
            ->paginate((int) request('per_page', 20));

        return [
            'pagination' => [
                'total'         => $clients->total(),
                'current_page'  => $clients->currentPage(),
                'per_page'      => $clients->perPage(),
                'last_page'     => $clients->lastPage(),
                'from'          => $clients->firstItem(),
                'to'            => $clients->lastItem(),
            ],
            'clients' => $clients
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
        $data = $request->all();
        $data['user_id'] = Auth::id();

        $client = Client::create($data);

        return $client->id;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::find($id);

        return $client;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Client::find($id)->update($request->all());

        return;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id);

        $dependencies = $this->collectDeleteDependencies($client->id);

        if (!empty($dependencies)) {
            return response()->json([
                'error' => 'No se puede eliminar el cliente porque tiene datos asociados: ' . implode(', ', $dependencies) . '.',
            ], 422);
        }

        try {
            $client->delete();
        } catch (\Throwable $exception) {
            return response()->json([
                'error' => 'No se pudo eliminar el cliente. Revisa si aun tiene datos asociados.',
            ], 422);
        }

        return response()->json([
            'message' => 'Cliente eliminado con exito',
        ]);
    }

    public function all()
    {
        $idUser = Auth::id();
        $client = Client::whereHas('user', function ($query) use ($idUser) {
            $query->where('id', '=', $idUser)
                ->where('clients.type', '<>', 'Cliente Particular');
        })->type()->orderBy('id', 'DESC')->get();

        return $client;
    }

    private function collectDeleteDependencies($clientId)
    {
        $dependencyMap = [
            'activities' => ['column' => 'client_id', 'label' => 'actividades'],
            'products' => ['column' => 'client_id', 'label' => 'productos'],
            'quotationclients' => ['column' => 'client_id', 'label' => 'cotizaciones formales'],
            'sales' => ['column' => 'client_id', 'label' => 'ventas'],
        ];

        $dependencies = [];

        foreach ($dependencyMap as $table => $config) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            $count = DB::table($table)->where($config['column'], $clientId)->count();

            if ($count > 0) {
                $dependencies[] = $count . ' ' . $config['label'];
            }
        }

        return $dependencies;
    }
}
