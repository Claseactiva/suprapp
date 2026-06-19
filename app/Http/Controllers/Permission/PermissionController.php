<?php

namespace App\Http\Controllers\Permission;

use Spatie\Permission\Models\Permission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PermissionController extends Controller
{

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::all();

        return $permissions;
    }

    public function all()
    {
        $permissions = Permission::all();

        return $permissions;
    }


    
}
