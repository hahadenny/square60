<?php
namespace App\Http\Controllers;

use App\Repositories\EstateInfoRepo;
use App\Repositories\BuildingRepo;
use App\Repositories\SearchRepo;
use App\Repositories\ShowRepo;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;


class SendController extends Controller
{
    private $estate;
    private $search;
    private $building;
    private $showRepo;

    public function __construct(EstateInfoRepo $estate,SearchRepo $search,BuildingRepo $building,ShowRepo $showRepo)
    {
        $this->estate = $estate;
        $this->search = $search;
        $this->building = $building;
        $this->showRepo = $showRepo;
    }

    public function sendEmail(Request $request)
    {
        if(!empty($request)){
            $data = $request;

            $validator = Validator::make($data->all(), [
                'useremail' => 'required|email',
            ]);
    
            if ($validator->fails()) {
                return 'Please enter a valid email.';
            }
            
            //URL::route('main')
            if ($data['type'] == 'building') {
                $advertData = $this->building->getBuildingById($data['listingid']);
                if($advertData){
                    $data['advertLink'] = env('APP_URL').'building/'.str_replace(' ','_',$advertData['building_name']).'/'.str_replace(' ','_',$advertData['building_city']);
                }
            }
            else {
                $advertData = $this->estate->findListing($data['listingid']);
                if($advertData){
                    $data['advertLink'] = env('APP_URL').'show/'.str_replace(' ','_',$advertData['name']).'/'.$data['listingid'];
                }
            }
            if(!empty($data['useremail']) && !empty($data['message'])){
                if(!empty($data['agentemail'])){
                    $domain = env('APP_URL');
                    $data['subject'] = 'You have a message';
                    $emails = explode(',', $data['agentemail']);
                    foreach ($emails as $secondEmail){
                        $email = trim($secondEmail);
                        if ($email)
                            Mail::to($secondEmail)->send(new \App\Mail\SendEmail($data, $domain));
                    }
                }else{
                    $emails = env('DEMO_EMAIL');
                    $emails = explode(',',$emails);
                    $domain = env('APP_URL');
                    $data['subject'] = 'You have a message';
                    foreach ($emails as $secondEmail){
                        Mail::to($secondEmail)->send(new \App\Mail\SendEmail($data, $domain));
                    }
                }
                return $response = 'Your message is sent successfully!';
            }else{
                return $response = 'Please fill in all data.';
            }
        }else{
            return $response = 'Error, message didn\'t send.';
        }
    }

    public function sendResult(Request $request)
    {

        if(!empty($request->post())){

            $email = $request->email;

            if(empty($email)){
                return $response = 'Please enter your email.';
            }

            $result = $this->search->getSearchresults($request->searchid);

            $ids = $result->ids;

            $idsArray = array();
            if (strlen($ids)){
                $idsArray = explode(',',$ids);
            }
            $model = $this->estate->queryEstate();
            if (count($idsArray)){
                $model->whereIn('id',$idsArray);
            }else {
                $requestData = $result->request;
                $requestData = json_decode($requestData,true);

                $this->search->_prepareModel($model,$requestData);
            }

            $results = $model->take(env('SEARCH_RESULTS_PER_PAGE'))->orderBy('estate_data.created_at','desc')->with('openHouse')->get();

            $results = $this->search->_prepareResults($results);

            //$domain = URL::route('main');
            $domain = env('APP_URL');
            if ($results[0]->estate_type==2)
                $subject = "Rentals Search Results";
            else
                $subject = "Sales Search Results";
            Mail::to($email)->send(new \App\Mail\ResultMail($results, $domain, $subject, '', $request->searchid));

            return $response = 'Result sent.';

        }else{
            return $response = 'Error, result didn\'t send.';
        }
    }

    public function sendList(Request $request)
    {

        if(!empty($request->post())){
            $email = $request->email;

            if(empty($email)){
                return $response = 'Please enter your email.';
            }

            $details = $this->showRepo->show($request, '', $request->listing_id);
            $details['result']->img = $details['images'][0];

            //return $details['result']->img;  //for testing

            $domain = env('APP_URL');
            if ($details['result']->estate_type==2)
                $subject = "Rental List: ".$details['result']->full_address.' '.$details['result']->unit;
            else
                $subject = "Sale List: ".$details['result']->full_address.' '.$details['result']->unit;

            Mail::to($email)->send(new \App\Mail\ListMail($details['result'], $details['agents'], $domain, $subject));

            return $response  = 'List sent.';
        }else{
            return $response = 'Error, list didn\'t send.';
        }
    }

    public function sendBuilding(Request $request)
    {

        if(!empty($request->post())){
            $email = $request->email;

            if(empty($email)){
                return $response = 'Please enter your email.';
            }

            $result = $this->building->getBuilding($request->building_name, $request->building_city);
            $details = $this->showRepo->showBuilding($result, '', '');
            $details['result']->img = $details['images'][0];

            //return $details['result']->img;  //for testing

            $domain = env('APP_URL');
            $subject = "Building: ".$details['result']->building_name;

            Mail::to($email)->send(new \App\Mail\BuildingMail($details['result'], $details['agents'], $domain, $subject));

            return $response  = 'Building sent.';
        }else{
            return $response = 'Error, list didn\'t send.';
        }
    }

    public function sendInvitation(){

       $users = User::with('userAgent')->get();

       foreach($users as $user)
       {
           if(!empty($user->userAgent))
           {
               Mail::to($user->email)->send(new \App\Mail\InvitationMail($user));
           }
       }
    }
}
