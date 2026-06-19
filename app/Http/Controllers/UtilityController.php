<?php

namespace App\Http\Controllers;

use App\Models\Utility;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UtilityController extends Controller
{
    public function index()
    {
        $latestUtility = Utility::latest()->first();

        return $latestUtility;
    }

    public function store(Request $request)
    {
        $id_user = Auth::id();

        $utilidad = Utility::create($request->all());
        Product::with('client')->withUserClients($id_user)->update(['utilidad' => $utilidad->utilidad]);

        return $utilidad->utilidad;
    }
}
