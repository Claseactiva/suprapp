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
        $latestFlete = Flete::where('user_id', Auth::user()->effectiveTallerId())->latest()->first();

        return $latestFlete;
    }

    public function store(Request $request)
    {
        $id_user = Auth::id();

        $flete = Flete::create(array_merge($request->all(), ['user_id' => $id_user]));
        Product::with('client')->withUserClients($id_user)->update(['flete' => $flete->flete]);

        return $flete->flete;
    }
}
