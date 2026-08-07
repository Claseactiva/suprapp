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
        $latestUtility = Utility::where('user_id', Auth::id())->latest()->first();

        return $latestUtility;
    }

    public function store(Request $request)
    {
        $id_user = Auth::id();

        $utilidad = Utility::create(array_merge($request->all(), ['user_id' => $id_user]));
        Product::with('client')->withUserClients($id_user)->update(['utilidad' => $utilidad->utilidad]);

        return $utilidad->utilidad;
    }
}
