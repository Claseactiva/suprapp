<?php

namespace App\Http\Controllers;


use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            ->paginate(10);

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
        $client->delete();

        return;
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
}
