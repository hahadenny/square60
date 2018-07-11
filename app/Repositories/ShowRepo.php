<?php

namespace App\Repositories;

use App\SavedItems;
use Illuminate\Support\Facades\Auth;

class ShowRepo
{
    public $estate;
    private $agent;
    private $user;

    public function __construct(EstateInfoRepo $estate, AgentRepo $agentRepo, UserRepo $userRepo)
    {
        $this->estate = $estate;
        $this->agent = $agentRepo;
        $this->user = $userRepo;
    }

    public function show($request, $name, $id)
    {
        $result = $this->estate->getListing($id);

        if (!$result || (!$result->active)){
            return view('404');
        }

        $this->estate->incrementListing($result);

        if ($result->unit_description == 'NULL'){ // || strlen($result->unit_description) < 20
            $result->unit_description = false;
        }else {
            $result->unit_description = trim($result->unit_description,'"');
            $result->unit_description = trim($result->unit_description);
        }

        if ($result->amazon_id == 0) {
            $images = $result->path_for_large;
        }else{
            $images = $result->path_for_images;
        }
        $images = @json_decode($images);
        if ($images){
            foreach ($images as $key=>$row){
                if (!empty($row)){
                    if ($result->estate_type==2 && $result->amazon_id == 0){
                        $images[$key] =  env('S3_IMG_PATH').'images-rental/'.$row;
                    }elseif($result->estate_type==2 && $result->amazon_id == 1){
                        $images[$key] = env('S3_IMG_PATH_1').$row;
                    }

                    if ($result->estate_type==1 && $result->amazon_id == 0){
                        $images[$key] =  env('S3_IMG_PATH').'sales-listing-images/'.$row;
                    }elseif($result->estate_type==1 && $result->amazon_id == 1){

                        $images[$key] = env('S3_IMG_PATH_1').$row;
                    }
                }else {
                    unset($images[$key]);
                }
            }
        }else {
            $images = array();
        }
        $floors = $result->path_for_floorplans;
        $floors = @json_decode($floors);

        if ($floors){
            foreach ($floors as $k=>$fimage){
                if (!empty($fimage)){
                    if ($result->estate_type == 2 && $result->amazon_id == 0){
                        $floors[$k] =  env('S3_IMG_PATH').'images-rental/'.$fimage;
                    }elseif($result->estate_type == 2 && $result->amazon_id == 1){
                        $floors[$k] =  env('S3_IMG_PATH_1').$fimage;
                    }
                    if ($result->estate_type==1 && $result->amazon_id == 0){
                        $floors[$k] =  env('S3_IMG_PATH').'sales-listing-images/'.$fimage;
                    }elseif($result->estate_type == 1 && $result->amazon_id == 1){
                        $floors[$k] =  env('S3_IMG_PATH_1').$fimage;
                    }
                }else {
                    unset($floors[$k]);
                }
            }
        }else {
            $floors = array();
        }

        $result->path_for_floorplans = $floors;
        $result->tax = number_format($result->monthly_taxes,'0','.',',');
        $result->maint = number_format($result->maintenance + $result->commong_charges,'0','.',',');
        $result->listed = date('d M Y',strtotime($result->created_at));
        $result->amenities = explode('|',$result->amenities);
        $currentAvailableSales = $this->currentAvailable(1, $result->building_id, $result->id);
        $currentAvailableRentals = $this->currentAvailable(2, $result->building_id, $result->id);

        if ($result->amazon_id == 0) {
            $agents = $this->_getAgents($result->listing_id, $result->estate_type, true);
        }else{
            $agents = $this->_curAgents($result->user_id);

            if($agents->isEmpty()){
                $user = $this->user->getUser($result->user_id);
                $agents []= (object)array(
                    "name" => $user->name,
                    "email" => $user->email,
                    "role_id" => $user['roles'][0]['id']
                );
            }
        }

        //if (!empty($result->logo->logo_path)){
        if (!empty($result->logo_path) && $result->logo_path && $result->logo_path != 'NULL'){
            //$result->path_to_logo = env('S3_IMG_PATH').'images-rental/'.$result->logo->logo_path;
            $result->path_to_logo = env('S3_IMG_PATH').'square60/'.$result->logo_path;
        }
        elseif(isset($agents[0]) && isset($agents[0]->logo_path) && $agents[0]->logo_path && $agents[0]->logo_path != 'NULL') {
            $result->path_to_logo = env('S3_IMG_PATH').'square60/'.$agents[0]->logo_path;
        }
        else {
            $result->path_to_logo = '';
        }

        $saved = false;
        if (!Auth::guest()) {
            $saved = SavedItems::where('user_id', '=', Auth::user()->id)->where('type', '=', $result->estate_type)->where('save_id', '=', $result->id)->get();
            //print_r(count($saved)); exit;
            if (count($saved)) {
                $saved = true;
            } 
            else
                $saved = false;
        }

        return compact('result','images', 'agents','currentAvailableSales', 'currentAvailableRentals', 'name', 'saved');
    }

    protected function _getAgents($buidingId, $type, $isListing = false){

        if ($isListing){
            $agentsIds = $this->agent->getListingAgents($buidingId);
        }else {
            $agentsIds = $this->agent->getBuildingAgents($buidingId);
        }

        foreach ($agentsIds as &$agent){ 
            $agent->name = $agent->full_name;
            if (strlen($agent->photo) && $agent->photo != 'default/agent_no_photo_64x64.png'){
                if ($type==2){
                    //$agent->img = env('S3_IMG_PATH').'images-rental/'.$agent->photo;
                    $agent->img = env('S3_IMG_PATH').'sales-listing-images/'.$agent->photo;
                }
                if ($type==1){
                    $agent->img = env('S3_IMG_PATH').'sales-listing-images/'.$agent->photo;
                }
            }else{
                $agent->img = '/images/default_agent.jpg';
            }
            if (!empty($agent->logo_path) && $agent->logo_path != 'NULL'){
                $agent->path_to_logo = env('S3_IMG_PATH').'square60/'.$agent->logo_path;
            }else {
                $agent->path_to_logo = '';
            }
            $agent->company_name = $agent->company;
            $agent->phone = $agent->office_phone;
            $agent->email = '';
        }
        return $agentsIds;
    }

    protected function _curAgents($user_id){

        $agents = $this->agent->getCurrentAgent($user_id);
        //print_r($agents[0]->user->premium); exit;

        foreach($agents as &$agent) {
            $agent->full_name = $agent->first_name . ' ' . $agent->last_name;
            if (strlen($agent->photo)) {
                $agent->img = env('S3_IMG_PATH_1') . $agent->photo_url;
            } else {
                $agent->img = false;
            }
            if (!empty($agent->logo_path) && $agent->logo_path != 'NULL'){
                $agent->path_to_logo = env('S3_IMG_PATH_1').$agent->logo_path;
            }else {
                $agent->path_to_logo = '';
            }
            $agent->company_name = $agent->company;
            $agent->phone = $agent->office_phone;
            $agent->email = $agent->user->email;
            //print_r($agent); exit;
        }
        return $agents;
    }
    protected function currentAvailable($estate_type, $building_id, $sale_id){

        $currentAvailable = $this->estate->currentAvailebleListing($estate_type, $building_id, $sale_id);

        foreach ($currentAvailable as $item){
            $imagesArray  = @json_decode($item->path_for_images,true);

            if ($imagesArray && !empty($imagesArray)){
                $img = $imagesArray[0];
                if ($item->estate_type == 2 && $item->amazon_id == 0){
                    $item->path_for_images =  env('S3_IMG_PATH').'images-rental/'.$img;
                }elseif($item->estate_type == 2 && $item->amazon_id == 1){
                    $item->path_for_images =  env('S3_IMG_PATH_1').$img;
                }
                if ($item->estate_type==1 && $item->amazon_id == 0){
                    $item->path_for_images =  env('S3_IMG_PATH').'sales-listing-images/'.$img;
                }elseif($item->estate_type == 1 && $item->amazon_id == 1){
                    $item->path_for_images =  env('S3_IMG_PATH_1').$img;
                }
            }else {
                $item->path_for_images = "/images/default_image_coming.jpg"; // not found img
            }
        }
        return $currentAvailable;
    }

    public function showBuilding($result, $name, $city){

        $images = $result->path_for_building_images_on_s3;
        $images = @json_decode($images);

        if ($images){
            foreach ($images as $key=>$row){
                if (!empty($row) && strlen($row)){
                    if ($result['b_listing_type']==2 && $result['amazon_id'] == 0){
                        $images[$key] =  env('S3_IMG_PATH').'images-rental/'.$row; //for rentals listings
                    }elseif($result['b_listing_type']==2 && $result['amazon_id'] == 1){
                        $images[$key] = env('S3_IMG_PATH_1').$row;
                    }
                    if ($result['b_listing_type']==1 && $result['amazon_id'] == 0) {  // building for sales listings
                        $images[$key] = env('S3_IMG_PATH') . 'sales-listing-images/' . $row;
                    }elseif($result['b_listing_type']==1 && $result['amazon_id'] == 1){
                        $images[$key] = env('S3_IMG_PATH_1').$row;
                    }
                }else {
                    unset($images[$key]);
                }
            }
        }else {
            $images = array();
        }

        if (empty($images)){
            $images = "/images/default_image_coming.jpg"; // not found img)
        }

        //for name label image:
        $name_label = $result->path_for_name_label_image;
        $name_label = @json_decode($name_label);

        if ($name_label){
            foreach ($name_label as $key=>$row){
                if (!empty($row) && strlen($row)){                    
                    $name_label[$key] = env('S3_IMG_PATH_1').$row;                    
                }else {
                    unset($name_label[$key]);
                }
            }
        }else {
            $name_label = array();
        }        

        $result->building_amenities  = explode('|', $result->building_amenities);

        $estate = $this->estate->getRelatedBuilding($result->building_id);

        $result->neighborhood = isset($estate->neighborhood) ? $estate->neighborhood : '';

        $currentAvailableSales = $this->currentAvailableInBuilding($result->building_id, 1);
        $currentAvailableRentals = $this->currentAvailableInBuilding($result->building_id, 2);

        $agents = $this->getBuildingAgents($result->building_id,$result->b_listing_type);

        $saved = false;
        if (!Auth::guest() && $name && $city) {
            $saved = SavedItems::where('user_id', '=', Auth::user()->id)->where('type', '=', '3')->where('save_id', '=', $name)->where('save_id2', '=', $city)->get();
            //print_r(count($saved)); exit;
            if (count($saved)) {
                $saved = true;
            } 
            else
                $saved = false;
        }

        return compact('result','images', 'name_label', 'currentAvailableSales', 'currentAvailableRentals','agents', 'name', 'city', 'saved');
    }

    protected function currentAvailableInBuilding($building_id,$estate_type){

        $currentAvailable = $this->estate->getBuildingListings($building_id,$estate_type);

        foreach ($currentAvailable as &$item){
            $images = $item->path_for_images;
            $images = json_decode($images);


            if (count($images)){
                $img = array_shift($images); //use first images
                if (!empty($img)){
                    if ($item->estate_type==2){
                        if ($item->amazon_id == 0)
                            $item->img =  env('S3_IMG_PATH').'images-rental/'.$img;
                        elseif ($item->amazon_id == 1)
                            $item->img =  env('S3_IMG_PATH_1').$img;
                    }
                    elseif ($item->estate_type==1){
                        if ($item->amazon_id == 0)
                            $item->img =  env('S3_IMG_PATH').'sales-listing-images/'.$img;
                        elseif ($item->amazon_id == 1)    
                            $item->img = env('S3_IMG_PATH_1').$img;
                    }
                }
            }else{
                $item->img = "/images/default_image_coming.jpg"; // not found img
            }

            $item->name_link = '/show/'.str_replace(' ','_',$item->name).'/'.$item->id;

        }
        return $currentAvailable;
    }

    protected function getBuildingAgents($buidingId, $type){

        $agentsIds = $this->agent->getBuildingAgents($buidingId);

        foreach ($agentsIds as &$agent){

            $agent->name = $agent->full_name;

            if (strlen($agent->photo)){
                if ($type==2){
                    $agent->img = env('S3_IMG_PATH').'images-rental/'.$agent->photo;
                }
                if ($type==1){
                    $agent->img = env('S3_IMG_PATH').'sales-listing-images/'.$agent->photo;
                }
            }else{
                $agent->img = false;
            }
            if (!empty($agent->logo_path)){
                $agent->path_to_logo = env('S3_IMG_PATH').'square60/'.$agent->logo_path;
            }else {
                $agent->path_to_logo = '';
            }
            $agent->compmay_name = $agent->company;
            $agent->phone = $agent->office_phone;
        }

        return $agentsIds;
    }

    public function saveListing($request) {
        //echo $request->user_id; exit;
        $result = SavedItems::where('user_id', '=', $request->user_id)->where('type', '=', $request->type)->where('save_id', '=', $request->save_id)->get();

        //print_r($result); exit;

        if (count($result)) { // already saved
            return false;        
        }
        else {
            $saveItem = new SavedItems();
            $saveItem->user_id = $request->user_id;
            $saveItem->type = $request->type;
            $saveItem->save_id = $request->save_id;
            $saveItem->created_at = new \DateTime();
            $saveItem->updated_at = new \DateTime();
            $saveItem->save();
        }

        return true;
    }

    public function saveBuilding($request) {
        //echo $request->user_id; exit;
        $result = SavedItems::where('user_id', '=', $request->user_id)->where('type', '=', $request->type)->where('save_id', '=', $request->name)->where('save_id2', '=', $request->city)->get();

        //print_r($result); exit;

        if (count($result)) { // already saved
            return false;        
        }
        else {
            $saveItem = new SavedItems();
            $saveItem->user_id = $request->user_id;
            $saveItem->type = $request->type;
            $saveItem->save_id = $request->name;
            $saveItem->save_id2 = $request->city;
            $saveItem->created_at = new \DateTime();
            $saveItem->updated_at = new \DateTime();
            $saveItem->save();
        }

        return true;
    }
}