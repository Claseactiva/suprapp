<?php

namespace App\Http\Controllers;

use App\Models\Flete;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FleteController extends Controller
{
    public function index()
    {
        $latestFlete = Flete::latest()->first();

        return $latestFlete;
    }

    public function store(Request $request)
    {
        $id_user = Auth::id();

        $flete = Flete::create($request->all());
        Product::with('client')->withUserClients($id_user)->update(['flete' => $flete->flete]);

        return $flete->flete;
    }
}
