<?php

namespace App\Repositories;

use App\User;
use App\Role;
use App\Feature;
use App\Premium;
use App\EstateInfo;
use App\AgentInfo;
use App\Membership;
use App\Failed;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Error\Card;

class UserRepo
{
    public function usersList()
    {
        return User::paginate(env('SEARCH_RESULTS_PER_PAGE'));
    }

    public function getUser($id)
    {
        return User::with('roles')
                    ->with(array('memberships' => function($query) {
                        $query->whereNotIn('status', ['renew_failed', 'ended', 'renewed'])->orderBy('type', 'DESC')->orderBy('renew', 'DESC')->orderBy('ends_at', 'DESC');
                    }))
                    ->find($id);
    }

    public function getRoles()
    {
        return Role::all();
    }

    public function updateUser($request)
    {
        $user = $this->getUser($request->id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2',
            'email' => 'required|email',
        ]);

        if(!empty($request->password)){
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|min:6|confirmed',
            ]);

            $user->password = Hash::make($request->password);
        }

        if ($validator->fails()) {
            return redirect('/edituser')
                ->withErrors($validator)
                ->withInput();
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        //$user->roles()->updateExistingPivot($user->roles['0']->id, ['role_id' => $request->role]); //TODO If admin cann change role for user

        return $user->save();
    }

    public function deleteUser($id)
    {
        $user = $this->getUser($id);

        $user->roles()->detach();

        return $user->delete();
    }

    public function userAgent($id)
    {
        return User::with('userAgent')->find($id);
    }

    public function userExpert($user_id){

        //$user = $this->getUser($id);

        $membership = DB::table('memberships')->where('user_id', '=', $user_id)->where('status', 'success')->orderBy('type', 'DESC')->orderBy('renew', 'DESC')->orderBy('ends_at', 'DESC')->get()->first();

        return $membership;

        //return $user->subscribed('expert');
    }

    public function upgrateToExpert($request)
    {
        $period = $request->post('period');  //price
        //$isRecuring = $request->post('recuring'); //TODO auto update

        $user  = $this->getUser($request->user()->id);

        if($request->period == '1_1m'){
            $ends_at = Carbon::now()->addMonths(1);
            $price = env('SILV_1M');
        }elseif ($request->period == '1_1y'){
            $ends_at = Carbon::now()->addYears(1);
            $price = env('SILV_1Y');
        }elseif($request->period == '2_1m'){
            $ends_at = Carbon::now()->addMonths(1);
            $price = env('GOLD_1M');
        }elseif ($request->period == '2_1y'){
            $ends_at = Carbon::now()->addYears(1);
            $price = env('GOLD_1Y');
        }elseif($request->period == '3_1m'){
            $ends_at = Carbon::now()->addMonths(1);
            $price = env('DIAM_1M');
        }elseif ($request->period == '3_1y'){
            $ends_at = Carbon::now()->addYears(1);
            $price = env('DIAM_1Y');
        }

        $amount = $price * 100;

        try {
            $options = array('email'=>$user->email);
            $customer = $user->createAsStripeCustomer($request->post('stripeToken'), $options);

            $response = $user->charge($amount);
            //print_r($response->status); exit;

            if ($response->status != 'succeeded') {
                return 'failed';                
            }       
        } catch (Card $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            $err_msg = $err['message'];
            
            //log failed payment
            $failed = new Failed();
            $failed->user_id = $user->id;
            $failed->refer_id = '';
            $failed->type = 'membership_'.$request->type;
            $failed->renew = isset($request->recuring) ? $request->recuring : 0;
            $failed->amount = $price;
            $failed->period = $request->period;
            $failed->error = $err_msg;
            $failed->save();

            return 'failed';
        }

        if(!isset($request->recuring)){
            $recuring = 0;
        }else{
            $recuring = $request->recuring;
        }

        $membership = new Membership();

        $membership->stripe_id = $response->id;
        $membership->cus_id = $customer->id;
        $membership->user_id = $request->user()->id;
        $membership->renew = $recuring;
        $membership->ends_at = $ends_at;
        $membership->period = $request->period;
        $membership->amount = $price;
        $membership->type = $request->type;
        $membership->save();

        //send billing confirmation email
        $domain = env('APP_URL');
        $data = $membership;

        if ($request->type == 1)
            $mtype = 'Silver';
        elseif ($request->type == 2)
            $mtype = 'Gold';
        elseif ($request->type == 3)
            $mtype = 'Diamond';

        $subject = $mtype.' Membershiip Confirmation ID: '.$membership->id;
        Mail::to($user->email)->send(new \App\Mail\BillingMail($data, $domain, "$mtype Membership", $subject));

        $user = User::find($request->user()->id);

        if (!$user->premium || $request->type > $user->premium)
            $user->premium = $request->type;

        return $user->save();

        /*if ($period == 35){
            $plan = env('STRIPE_MONTH_EXPERT');
        }else{
            $plan = env('STRIPE_YEAR_EXPERT');
        }

        $stripeToken = $request->post('stripeToken');

        $user->newSubscription('expert', $plan)->create($stripeToken);

        return redirect('/upgrade');*/
    }

    public function featuring($request)
    {
        $user  = $this->getUser($request->user()->id);

        if($request->period == '1w'){
            $price = env('FEAT_1W');
            $ends_at = Carbon::now()->addWeek();
        }elseif ($request->period == '2w'){
            $price = env('FEAT_2W');
            $ends_at = Carbon::now()->addWeeks(2);
        }elseif ($request->period == '4w'){
            $price = env('FEAT_4W');
            $ends_at = Carbon::now()->addWeeks(4);
        }elseif ($request->period == '6w'){
            $price = env('FEAT_6W');
            $ends_at = Carbon::now()->addWeeks(6);
        }elseif ($request->period == '2m'){
            $price = env('FEAT_2M');
            $ends_at = Carbon::now()->addMonths(2);
        }

        $amount = $price * 100;

        if ($user->premium) {
            $price = $price * 0.75;
            $amount = $amount * 0.75;
        }

        try { 
            $options = array('email'=>$user->email);

            $customer = $user->createAsStripeCustomer($request->post('stripeToken'), $options);    

            //save stripe customer id  //saved already in function createAsStripeCustomer()
            /*DB::table('users')
            ->where('id', $user->id)
            ->update(['stripe_id' => $customer->id]);
            exit;*/

            $response = $user->charge($amount);
            
            //print_r($response); exit;
            //print_r($response->status); exit;

            if ($response->status != 'succeeded') {
                return 'failed';                
            }

            //test for recharge with customer id
            /*Stripe::setApiKey(env('STRIPE_SECRET'));
            $response = Charge::create(array(
                "amount" => 222,   //222 = 2.22
                "currency" => "usd",
                "customer" => $customer->id 
            ));
            print_r($response); exit;*/

        } catch (Card $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            $err_msg = $err['message'];
            
            //log failed payment
            $failed = new Failed();
            $failed->user_id = $user->id;
            $failed->refer_id = $request->id;
            $failed->type = 'feature';
            $failed->renew = isset($request->recuring) ? $request->recuring : 0;
            $failed->amount = $price;
            $failed->period = $request->period;
            $failed->error = $err_msg;
            $failed->save();

            return 'failed';
        }

        if(!isset($request->recuring)){
            $recuring = 0;
        }else{
            $recuring = $request->recuring;
        }

        $feature = new Feature();

        $feature->stripe_id = $response->id;
        $feature->cus_id = $customer->id;
        $feature->listing_id = $request->id;
        $feature->user_id = $request->user()->id;
        $feature->renew = $recuring;
        $feature->ends_at = $ends_at;
        $feature->period = $request->period;
        $feature->amount = $price;
        $feature->save();

        //send billing confirmation email
        $domain = env('APP_URL');
        $data = $feature;
        $subject = 'Feature Confirmation ID: '.$feature->id;
        Mail::to($user->email)->send(new \App\Mail\BillingMail($data, $domain, 'Feature', $subject));

        $estateInfo = EstateInfo::find($request->id);
        $estateInfo->feature = 1;

        return $estateInfo->save();
    }

    public function premiuming($request)
    {
        $user  = $this->getUser($request->user()->id);

        if($request->period == '1w'){
            $price = env('PREM_1W');
            $ends_at = Carbon::now()->addWeek();
        }elseif ($request->period == '2w'){
            $price = env('PREM_2W');
            $ends_at = Carbon::now()->addWeeks(2);
        }elseif ($request->period == '4w'){
            $price = env('PREM_4W');
            $ends_at = Carbon::now()->addWeeks(4);
        }

        $amount = $price * 100;

        /*if ($user->premium) {
            $price = $price * 0.75;
            $amount = $amount * 0.75;
        }*/

        try { 
            $options = array('email'=>$user->email);

            $customer = $user->createAsStripeCustomer($request->post('stripeToken'), $options);    

            $response = $user->charge($amount);

            if ($response->status != 'succeeded') {
                return 'failed';                
            }
        } catch (Card $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            $err_msg = $err['message'];
            
            //log failed payment
            $failed = new Failed();
            $failed->user_id = $user->id;
            $failed->refer_id = $request->id;
            $failed->type = 'premium';
            $failed->renew = isset($request->recuring) ? $request->recuring : 0;
            $failed->amount = $price;
            $failed->period = $request->period;
            $failed->error = $err_msg;
            $failed->save();

            return 'failed';
        }

        if(!isset($request->recuring)){
            $recuring = 0;
        }else{
            $recuring = $request->recuring;
        }

        $premium = new Premium();

        $premium->stripe_id = $response->id;
        $premium->cus_id = $customer->id;
        $premium->listing_id = $request->id;
        $premium->user_id = $request->user()->id;
        $premium->renew = $recuring;
        $premium->ends_at = $ends_at;
        $premium->period = $request->period;
        $premium->amount = $price;
        $premium->save();

        //send billing confirmation email
        $domain = env('APP_URL');
        $data = $premium;
        $subject = 'Premium Confirmation ID: '.$premium->id;
        Mail::to($user->email)->send(new \App\Mail\BillingMail($data, $domain, 'Premium', $subject));

        $estateInfo = EstateInfo::find($request->id);
        $estateInfo->premium = 1;

        return $estateInfo->save();
    }

    public function changeRole($request)
    {
        if ($request->type == 3) { //not allowed to change to Agent, need documents
            return false;
        }

        $user = $this->getUser($request->user()->id);

        if(!empty($request->type)){
             return $user->roles()->updateExistingPivot($user->roles['0']->id, ['role_id' => $request->type]);
        }
    }

    public function AgentInfo($id)
    {
        return AgentInfo::where('user_id', $id)->first();
    }

    public function updateAgentProfile($request){

        $user = $this->getUser($request->user()->id);
        $user->email = $request->email;
        $user->phone = $request->cellPhone;
        $user->save();

        $agent = $this->AgentInfo($request->user()->id);

        $addPhoto = $this->savePhoto($request, $agent);

        $addLogo = $this->saveLogo($request, $agent);

        //print_r($addLogo); exit;

        //update Listing logo and company
        DB::table('estate_data')->where('user_id', $user->id)->update(['agent_company' => $request->company, 'logo_path' => $addLogo]);

        if (!$request->fax)
            $request->fax = '';

        return AgentInfo::updateOrCreate(
            ['user_id' => $request->user()->id],
            ['last_name' => $request->lastName,
                'first_name' => $request->firstName,
                'full_name' => $request->firstName.' '.$request->lastName,
                'photo' => $addPhoto['imageFileName'],
                'photo_url' => $addPhoto['filePath'],
                'company' => $request->company,
                'web_site' => $request->webLink,
                'office_phone' => $request->officePhone,
                'fax' => $request->fax,
                'description' => $request->description,
                'logo_path'=> $addLogo]
        );
    }

    protected function savePhoto($request, $agent)
    {
        if($request->hasFile('photo')){

            $image = $request->file('photo');

            $imageFileName = uniqid(time()) . '.' . $image->getClientOriginalExtension();

            $s3 = Storage::disk('s3');

            if(!empty($agent)){
                if($s3->exists($agent->photo_url))
                {
                    $s3->delete($agent->photo_url);
                }
            }

            $filePath = 'agent_images/'.$request->user()->id.'/' . $imageFileName;

            $s3->put($filePath, file_get_contents($image), 'public');

        }else{
            if(!empty($agent->photo) && $agent->photo_url){
                $imageFileName = $agent->photo;

                $filePath = $agent->photo_url;
            }else{
                $imageFileName = '';

                $filePath = '';
            }
        }
        return compact('imageFileName', 'filePath');
    }

    protected function saveLogo($request, $agent)
    {
        if($request->hasFile('logo')){

            $logo = $request->file('logo');

            $imageFileName = uniqid(time()) . '.' . $logo->getClientOriginalExtension();
            $s3 = Storage::disk('s3');

            if(!empty($agent)){
                if($s3->exists($agent->logo))
                {
                    $s3->delete($agent->logo_path);
                }
            }

            $logoPath = 'logo/'.$request->user()->id.'/' . $imageFileName;

            $s3->put($logoPath, file_get_contents($logo), 'public');

        }else{
            if(!empty($agent->logo_path)){
                $logoPath = $agent->logo_path;
            }else{
                $logoPath = '';
            }
        }
        return $logoPath;
    }

    public function updateProfile($request)
    {
        $user = $this->getUser($request->user()->id);

        if (Hash::check($request->currentPassword, $user->password))
        {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);

            return $user->save();
        }else{
            return false;
        }
    }

    public function updateOwnerProfile($request)
    {
        $owner = $this->getUser($request->user()->id);

        if($owner)
        {
            $owner->name = $request->firstName;
            $owner->email = $request->email;
            $owner->phone = $request->cellPhone;

            return $owner->save();
        }else{
            return false;
        }
    }

    public function nameLabel($user_id, $building_id, $stripe_id, $cus_id, $type, $period, $amount, $ends_at, $renew)
    {
        return DB::table('name_label')->insert([
            'user_id' => $user_id, 
            'building_id' => $building_id,
            'stripe_id' => $stripe_id, 
            'cus_id' => $cus_id,
            'type' => $type,
            'period' => $period,
            'amount' => $amount,
            'ends_at' => $ends_at,
            'renew' => $renew,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
            ]);
    }

    public function chargeNameLabel($user_id, $amount)
    {
        $user  = $this->getUser($user_id);

        $amount = $amount * 100;

        try {
            return $response = $user->charge($amount);

        } catch (Exception $e) {
            //
        }
    }
}
