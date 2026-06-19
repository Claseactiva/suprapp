<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

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


    public function login(Request $request, $url)
    {
        $users = User::where('url', $url)->get();
        if ($users[0]->email == $request['email']) {
            $this->validateLogin($request);

            // If the class is using the ThrottlesLogins trait, we can automatically throttle
            // the login attempts for this application. We'll key this by the username and
            // the IP address of the client making these requests into this application.
            if (
                method_exists($this, 'hasTooManyLoginAttempts') &&
                $this->hasTooManyLoginAttempts($request)
            ) {
                $this->fireLockoutEvent($request);

                return $this->sendLockoutResponse($request);
            }

            if ($this->attemptLogin($request)) {
                return $this->sendLoginResponse($request);
            }

            // If the login attempt was unsuccessful we will increment the number of attempts
            // to login and redirect the user back to the login form. Of course, when this
            // user surpasses their maximum number of attempts they will get locked out.
            $this->incrementLoginAttempts($request);

            return $this->sendFailedLoginResponse($request);
        } else {
            return redirect()->route('acceso', ['url' => $url]);
        }
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


    public function showLoginForm($url = null)
    {
        if (isset($url)) {
            $users = User::where('url', $url)->get();
            foreach ($users as $user) {
                if (!empty($user->url)) {
                    if ($user->url == $url) {
                        return view('auth.login', ['url' => $url]);
                    } else {
                        return redirect()->route('acceso', ['url' => $user->url, 'name' => $user->name]);
                    }
                } else {
                    return redirect()->route('acceso', ['url' => $user->url, 'name' => $user->name]);
                }
            }
        } else {
            return redirect('error_ip');
        }
    }


    public function logout(Request $request, $url)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect()->route('acceso', ['url' => $url]);
    }
}
