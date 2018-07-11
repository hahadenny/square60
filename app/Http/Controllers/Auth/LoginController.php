<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;

use Auth;
use Illuminate\Http\Request;

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

    public function showLoginForm()
    {
        session(['link' => url()->previous()]);
        return view('auth.login');
    }


    protected function authenticated(Request $request, $user)
    {
        //update ipaddr
        $ipaddr = isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

        $country_city = '';
        $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$ipaddr"));
        if ($geo && isset($geo['geoplugin_status']) && $geo['geoplugin_status'] == '200') {
            $country = isset($geo["geoplugin_countryName"]) ? $geo["geoplugin_countryName"] : '';
            $city = isset($geo["geoplugin_city"]) && $geo["geoplugin_city"] ? $geo["geoplugin_city"] : isset($geo['geoplugin_region']) && $geo['geoplugin_region'] ? $geo['geoplugin_region'] : '';
            if ($country) {
                $country_city = $country;
                if ($city)
                    $country_city .= "/$city";
            }
        }

        DB::table('users')
            ->where('id', Auth::user()->id)
            ->update(['ipaddr' => $ipaddr, 'location' => $country_city]);

        if(Auth::user()->isAdmin()){
            return redirect(session('link'));
        }
        if(Auth::user()->isAgent())
        {
            return redirect('/home/profile');
        }elseif (Auth::user()->isOwner()){
            return redirect('/home/listing');
        }
        else {
            return redirect($this->redirectTo);
        }

    }

    public function logout(Request $request) {
        Auth::logout();
        return redirect('/');
    }

}
