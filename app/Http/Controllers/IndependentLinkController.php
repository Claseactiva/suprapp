<?php

namespace App\Http\Controllers;

use App\Models\IndependentLinkRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndependentLinkController extends Controller
{
    /**
     * Si el usuario logueado es el admin, sus propias solicitudes enviadas
     * (para pintar el estado del boton en admin-usuarios). Si no, las
     * solicitudes que le llegaron a el como cuenta independiente.
     */
    public function index()
    {
        $currentId = (int) Auth::id();

        if ($currentId === 1) {
            return IndependentLinkRequest::where('admin_id', 1)->get();
        }

        return IndependentLinkRequest::where('owner_user_id', $currentId)->get();
    }

    /**
     * El admin solicita vincularse con una cuenta independiente para poder
     * recibir cotizaciones compartidas.
     */
    public function store(Request $request)
    {
        abort_unless((int) Auth::id() === 1, 403);

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $owner = User::findOrFail($data['user_id']);
        abort_unless($owner->is_independent, 422, 'Esa cuenta no es independiente.');

        $link = IndependentLinkRequest::firstOrCreate(
            ['admin_id' => 1, 'owner_user_id' => $owner->id],
            ['status' => 'pending']
        );

        if ($link->status === 'rejected') {
            $link->update(['status' => 'pending']);
        }

        return response()->json($link);
    }

    /**
     * La cuenta independiente acepta la vinculacion.
     */
    public function accept($id)
    {
        $link = IndependentLinkRequest::findOrFail($id);
        abort_unless((int) Auth::id() === (int) $link->owner_user_id, 403);

        $link->update(['status' => 'accepted']);

        return response()->json($link);
    }

    /**
     * La cuenta independiente rechaza la vinculacion.
     */
    public function reject($id)
    {
        $link = IndependentLinkRequest::findOrFail($id);
        abort_unless((int) Auth::id() === (int) $link->owner_user_id, 403);

        $link->update(['status' => 'rejected']);

        return response()->json($link);
    }
}
