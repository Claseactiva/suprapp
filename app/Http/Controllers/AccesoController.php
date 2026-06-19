<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use Illuminate\Http\Request;

class AccesoController extends Controller
{

    public function index(Request $request, $url)
    {
        try {
            $users = User::where('url', $url)->get();
            if (count($users) > 0) {
                foreach ($users as $user) {
                    if ($user->url === $url) {
                        return view('acceso', ['url' => $user->url, 'name' => $user->name]);
                    } else {
                        return redirect('error_ip');
                    }
                }
            } else {
                return redirect('error_url');
            }
        } catch (Exception $e) {
            return $e;
        }
    }

    public function acceso(Request $request, $url)
    {
        try {
            $users = User::where('url', $url)->get();
            if (count($users) > 0) {
                foreach ($users as $user) {
                    if ($user->url === $url) {
                        return redirect()->route('login', ['url' => $user->url]);
                    } else {
                        return redirect('error_ip');
                    }
                }
            } else {
                return redirect('error_url');
            }
        } catch (Exception $e) {
            return $e;
        }
    }
}
