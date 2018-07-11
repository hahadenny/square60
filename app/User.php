<?php

namespace App;

use GuzzleHttp\Psr7\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Cashier\Billable;
use App\Notifications\MyOwnResetPassword as ResetPasswordNotification;

class User extends Authenticatable
{
    use Notifiable;
    use Billable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'ipaddr', 'location', 'premium'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function memberships()
    {
        return $this->hasMany('App\Membership','user_id', 'id');
    }

    public function searchResults()
    {
        return $this->belongsToMany(SearchResults::class,'user_search_results','user_id','search_id');
    }


    public function authorizeRoles($roles)
    {
        if (is_array($roles)) {
            return $this->hasAnyRole($roles) ||
                abort(401, 'This action is unauthorized.');
        }
        return $this->hasRole($roles) ||
            abort(401, 'This action is unauthorized.');
    }

    public function hasAnyRole($roles)
    {
        return null !== $this->roles()->whereIn('name', $roles)->first();
    }

    public function hasRole($role)
    {
        return null !== $this->roles()->where('name', $role)->first();
    }

    public function isAgent() {

        if($this->hasRole('Agent')){
            return true;
        }
        return false;
    }
    public function isOwner() {

        if($this->hasRole('Owner')){
            return true;
        }
        return false;
    }
    public function isUser() {

        if($this->hasRole('user')){
            return true;
        }
        return false;
    }
    public function isMan() {

        if($this->hasRole('man')){
            return true;
        }
        return false;
    }
    public function isAdmin(){
        if($this->hasRole('admin')){
            return true;
        }
        return false;
    }

    public function getUserResult(){
        if(!empty(Auth::id())){
            return $userResult = DB::table('user_search_results')->where('user_id', '=', Auth::id())
                ->get();
        }
    }

    public function getSavedItems($type) {
        if(!empty(Auth::id())){
            if ($type == 1 || $type == 2) //1=sales, 2=rentals
                return $userItems = DB::table('saved_items')->select('saved_items.*', 'estate_data.full_address', 'estate_data.unit')->join('estate_data', 'estate_data.id', '=', 'saved_items.save_id')->where('saved_items.user_id', '=', Auth::id())->where('saved_items.type', '=', $type)->get();
            elseif ($type == 3) { //3=buildings
                return $userItems = DB::select(DB::raw('select s.*, b.building_name from saved_items s inner join buildings b on b.building_name = replace(s.save_id, "_", " ") and b.building_city = replace(s.save_id2, "_", " ") where s.user_id = ? and s.type = ?'), [ Auth::id(), $type]);
            }
        }       
    }

    public function userAgent(){
        return $this->hasOne('App\AgentInfo');
    }

    public function isAgentVerified() {
        $agent = DB::table('agent_infos')->select('is_verified')->where('user_id', Auth::id())->get()->first();
        //echo $agent->is_verified; exit;
        if ($agent && $agent->is_verified)
            return true;
        else    
            return false;
    }
}
