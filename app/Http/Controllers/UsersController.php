<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Visits;
use Illuminate\Support\Carbon;

class UsersController extends Controller
{
    private $user;

    public function __construct(UserRepo $user)
    {
        $this->user = $user;
    }

    public function usersList(Request $request){

        $request->user()->authorizeRoles(['admin']);

        $usersList = $this->user->usersList();

        return view('showUsers', compact('usersList'));
    }

    public function edit(Request $request){

        $request->user()->authorizeRoles(['admin']);

        $userDate = $this->user->getUser($request->id);

        $roles = $this->user->getRoles();

        return view('editUser', compact('userDate', 'roles'));
    }

    public function update(Request $request)
    {
        $request->user()->authorizeRoles(['admin']);

        if ($this->user->updateUser($request)){
            return redirect('/users')->with('status', 'User updated successfully');
        }else{
            return redirect('/users')->with('status', 'Failed update, try again');
        }
    }

    public function delete(Request $request)
    {
       if ($this->user->deleteUser($request->id)){
           return redirect('/users')->with('status', 'User deleted');
       }else{
           return redirect('/users')->with('status', 'Failed to delete, try again');
       }
    }

    public function changeRole(Request $request)
    {
        if ($this->user->changeRole($request)){
            return redirect('/home')->with('status', 'Congratulation, Your status is changed successfully!');
        }else{
            return redirect('/home')->with('status', 'Failed to change your status, please try again!');
        }

    }

    public function logvisit(Request $request) {    
        $ipaddr = '';
        $country_city = '';
        if (!isset($_COOKIE['ipaddr'])) {
            $ipaddr = isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
            //$ipaddr = '74.88.121.162'; //for testing only

            setcookie(
                "ipaddr",
                $ipaddr,
                time() + (10 * 365 * 24 * 60 * 60)
              );
            
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

            setcookie(
                "location",
                $country_city,
                time() + (10 * 365 * 24 * 60 * 60)
            );
        }
        else {
            $ipaddr = $_COOKIE['ipaddr'];
            $country_city = isset($_COOKIE['location']) ? $_COOKIE['location'] : '';
        }

        //for different urls daily count
        if (!isset($_COOKIE[$request->url])) {
            setcookie(
                $request->url,
                1,
                time() + (1 * 24 * 60 * 60)
            );
            $day = Carbon::now()->format('Y-m-d');
            $visits = Visits::where('day', $day)->where('url', $request->url)->where('location', $country_city)->first();
            //print_r($visits); exit;
            if (!count($visits)) {
                $visits = new Visits();
                $visits->day = $day;
                $visits->url = $request->url;
                $visits->location = $country_city;
                $visits->visits = 1;
            }
            else
                $visits->visits = $visits->visits + 1;
            $visits->save();
        }

        //for unique visitors count
        if (!isset($_COOKIE['unique'])) {
            setcookie(
                'unique',
                1,
                time() + (10 * 365 * 24 * 60 * 60)
            );
            $day = Carbon::now()->format('Y-m-d');
            $visits = Visits::where('day', $day)->where('url', 'unique')->where('location', $country_city)->first();
            //print_r($visits); exit;
            if (!count($visits)) {
                $visits = new Visits();
                $visits->day = $day;
                $visits->url = 'unique';
                $visits->location = $country_city;
                $visits->visits = 1;
            }
            else
                $visits->visits = $visits->visits + 1;
            $visits->save();

            //log referer
            $referer = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : '';

            if ($referer) {
                $existed = DB::table('referers')->where('referer', $referer)->get()->count();

                if (!$existed)
                    DB::table('referers')->insert(
                        ['referer' => $referer, 'count' => 1]
                    );
                else
                    DB::table('referers')
                        ->where('referer', $referer)
                        ->increment('count');
            }
        }

        //for daily visitors count
        if (!isset($_COOKIE['daily'])) {
            setcookie(
                'daily',
                1,
                time() + (1 * 24 * 60 * 60)
            );
            $day = Carbon::now()->format('Y-m-d');
            $visits = Visits::where('day', $day)->where('url', 'daily')->where('location', $country_city)->first();
            //print_r($visits); exit;
            if (!count($visits)) {
                $visits = new Visits();
                $visits->day = $day;
                $visits->url = 'daily';
                $visits->location = $country_city;
                $visits->visits = 1;
            }
            else
                $visits->visits = $visits->visits + 1;
            $visits->save();
        }
    }
}
