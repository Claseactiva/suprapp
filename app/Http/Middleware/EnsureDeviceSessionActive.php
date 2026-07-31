<?php

namespace App\Http\Middleware;

use App\Models\UserSession;
use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureDeviceSessionActive
{
    /**
     * Si el dispositivo de esta sesion fue revocado desde "Mis Dispositivos"
     * (en este u otro navegador), se cierra la sesion inmediatamente.
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $deviceSessionId = $request->session()->get('device_session_id');

            if ($deviceSessionId) {
                $userSession = UserSession::find($deviceSessionId);

                if (!$userSession || $userSession->revoked_at !== null) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('login')
                        ->withErrors(['error' => 'Tu sesion fue cerrada desde otro dispositivo.']);
                }
            }
        }

        return $next($request);
    }
}
