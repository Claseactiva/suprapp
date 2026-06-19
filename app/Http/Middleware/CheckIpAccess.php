<?php

namespace App\Http\Middleware;
use App\User;
use Illuminate\Support\Facades\DB;

use Closure;

class CheckIpAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $id = $request->route()->parameter('url');
        $ip = request()->ip();
        
        $accesos = DB::table('users')->where([
                        ['id', '=', $id],
                        ['ip_acceso', '=', $ip]])
                        ->get();
        if(isset($id)){
            if(!$accesos->isEmpty()){
                foreach ($accesos as $acceso) {
                    if($acceso->ip_acceso == $ip){
                        return $next($request);
                    }else{
                        return redirect('error_ip');
                    }
                }
            }else{
                return $next($request);
            }
        }else{
            return redirect('error_ip');
        }
    }
}
