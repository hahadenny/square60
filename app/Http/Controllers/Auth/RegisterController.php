<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Role;
use App\AgentInfo;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        if($data['type'] == 3){
            $rule = [
                'name' => 'required|string|max:255',
                'phone' => 'required|numeric|min:10',
                'email' => 'required|string|email|max:255|unique:users|confirmed',
                'password' => 'required|string|min:6|confirmed'
            ];

            if (isset($data['web_site']) && $data['web_site']) {
                $rule['web_site'] = 'required|min:10';
            }
            else    
                $rule['license'] = 'required|image|max:20480';
            
            return Validator::make($data, $rule);
        }elseif($data['type'] == 5 || $data['type'] == 2){
            return Validator::make($data, [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users|confirmed',
                'password' => 'required|string|min:6|confirmed',
            ]);
        }elseif($data['type'] == 1){
            return Validator::make($data, [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ]);
        }

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
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

        //print_r($data); exit;

        $user = User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'ipaddr' => $ipaddr,
            'location' => $country_city
        ]);
        $user->roles()
             ->attach(Role::where('id', $data['type'])->first());

        //add to agent_infos table;
        if ($data['type'] == 3) {
            $request = (object) $data;
            $addPhoto = $this->savePhoto($user->id, $data);

            if (!$data['web_site'])
                $data['web_site'] = '';

            $agent = new AgentInfo();
            $agent->full_name = $data['name'];
            $agent->user_id = $user->id;
            $agent->license = $addPhoto['filePath'];
            $agent->web_site = $data['web_site'];
            $agent->save();
        }

        //$data['email'] = 'denny0709@hotmail.com'; //testing
        $domain = env('APP_URL');
        Mail::to($data['email'])->send(new \App\Mail\WelcomeMail($user, $domain));

        return $user;
    }

    protected function savePhoto($user_id, $data)
    {
        if(isset($data['license']) && $data['license'] && is_file($data['license'])){

            $image = $data['license'];

            //print_r($image); exit;

            $imageFileName = uniqid(time()) . '.' . $image->getClientOriginalExtension();

            $s3 = Storage::disk('s3');

            $filePath = 'licenses/'.$user_id.'/' . $imageFileName;

            $s3->put($filePath, file_get_contents($image), 'public');

        }else{
            $imageFileName = '';
            $filePath = '';
        }
        return compact('imageFileName', 'filePath');
    }
}
