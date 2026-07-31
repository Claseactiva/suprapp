<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\DeviceLimitReachedException;
use App\Models\UserSession;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            try {
                $this->registerDeviceSession($request);
            } catch (DeviceLimitReachedException $e) {
                $this->guard()->logout();
                $request->session()->invalidate();

                throw ValidationException::withMessages([
                    $this->error() => [
                        "Alcanzaste el limite de {$e->limit} dispositivos permitidos. Cierra sesion en otro dispositivo desde \"Mis Dispositivos\" antes de continuar."
                    ],
                ]);
            }

            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Reconoce o registra el dispositivo desde el que se hizo login,
     * y corta el login si ya se alcanzo el limite de dispositivos.
     *
     * @throws DeviceLimitReachedException
     */
    protected function registerDeviceSession(Request $request)
    {
        $user = $this->guard()->user();

        $fingerprint = trim((string) $request->input('device_fingerprint'));
        if ($fingerprint === '') {
            $fingerprint = Str::random(32);
        }
        $deviceName = trim((string) $request->input('device_name'));
        if ($deviceName === '') {
            $deviceName = 'Dispositivo';
        }
        $ipAddress = (string) $request->ip();
        $userAgent = substr((string) $request->userAgent(), 0, 255);

        $knownDevice = UserSession::active()
            ->where('user_id', $user->id)
            ->where('device_fingerprint', $fingerprint)
            ->first();

        // Si el fingerprint no coincide (modo incognito, datos borrados, etc.),
        // se intenta reconocer el mismo dispositivo por IP + user agent antes
        // de contarlo como uno nuevo.
        if (!$knownDevice) {
            $knownDevice = UserSession::active()
                ->where('user_id', $user->id)
                ->where('ip_address', $ipAddress)
                ->where('user_agent', $userAgent)
                ->where('last_seen_at', '>=', now()->subHours(24))
                ->first();
        }

        if (!$knownDevice) {
            $activeCount = UserSession::active()->where('user_id', $user->id)->count();
            $limit = max(1, (int) $user->device_limit);

            if ($activeCount >= $limit) {
                $activeSessions = UserSession::active()
                    ->where('user_id', $user->id)
                    ->orderByDesc('last_seen_at')
                    ->get();

                throw new DeviceLimitReachedException($limit, $activeSessions);
            }

            $knownDevice = new UserSession(['user_id' => $user->id]);
        }

        $knownDevice->fill([
            'session_token' => Str::random(64),
            'device_fingerprint' => $fingerprint,
            'device_name' => $deviceName,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'last_seen_at' => now(),
            'revoked_at' => null,
        ]);
        $knownDevice->save();

        $request->session()->put('device_session_id', $knownDevice->id);
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->email() => 'required|string',
            $this->password() => 'required|string',
        ]);
    }


    public function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->error() => [trans('¡Email o contraseña incorrecta!')],
        ]);
    }

    public function error()
    {
        return 'error';
    }

    public function email()
    {
        return 'email';
    }

    public function password()
    {
        return 'password';
    }


    public function showLoginForm()
    {
        return view('auth.login');
    }


    public function logout(Request $request)
    {
        $deviceSessionId = $request->session()->get('device_session_id');
        if ($deviceSessionId) {
            UserSession::where('id', $deviceSessionId)->update(['revoked_at' => now()]);
        }

        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect()->route('login');
    }
}
