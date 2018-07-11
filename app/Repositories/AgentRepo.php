<?php

namespace App\Repositories;

use App\AgentInfo;
use App\AgentsListings;
use App\AgentsBuildings;
use App\EstateInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AgentRepo
{

    public function getAgent($id)
    {
        return AgentInfo::with('user')->find($id);
    }

    public function getAgentProfile($id)
    {
        $agent = array($this->getAgent($id));

        if(!$agent[0]){
            return view('404');
        } else {
            $agent = $agent[0];
        }

        if (strlen($agent->photo) && $agent->photo != 'default/agent_no_photo_64x64.png'){

            if ($agent->user_id != 0){
                $agent->img = env('S3_IMG_PATH_1') . $agent->photo_url;
            } else {
                $hdrs = @get_headers(env('S3_IMG_PATH') . 'images-rental/' . $agent->photo);
                if(is_array($hdrs) ? preg_match('/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/',$hdrs[0]) : false){
                    $agent->img = env('S3_IMG_PATH') . 'images-rental/' . $agent->photo;
                } else {
                    $agent->img = env('S3_IMG_PATH') . 'sales-listing-images/' . $agent->photo;
                }
            }

        } else {
            $agent->img = '/images/default_agent.jpg';
        }

        if (!empty($agent->logo_path)){
            $agent->path_to_logo = env('S3_IMG_PATH_1').$agent->logo_path;
        }else {
            $agent->path_to_logo = '';
        }

        if(!$agent->full_name){
            $agent->full_name = $agent->first_name . ' ' . $agent->last_name;
        }

        if($agent->user){
            $agent->email = $agent->user['email'];
        } else {
            $agent->email = '';
        }

        return $agent;
    }

    public function agentsList()
    {
        return AgentInfo::paginate(env('SEARCH_RESULTS_PER_PAGE'));
    }

    public function getAgentPhoto($agent){

        return DB::table('agents_buildings')
                ->join('buildings', 'agents_buildings.building_id', '=', 'buildings.building_id')
                ->join('agent_infos','agents_buildings.agent_id', '=', 'agent_infos.external_id')
                ->where('agents_buildings.agent_id', '=', $agent->external_id)
                ->select('buildings.b_listing_type')
                ->get();
    }

    public function updateAgent($request){

        $agent = $this->getAgent($request->id);

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

            $filePath = 'agent_images/'.$agent->user_id.'/' . $imageFileName;

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

        $user_id = $agent->user_id;

        $agent->user_id = $user_id;
        $agent->last_name = $request->lastName;
        $agent->first_name = $request->firstName;
        $agent->photo = $imageFileName;
        $agent->photo_url = $filePath;
        $agent->company = $request->company;
        $agent->web_site = $request->webLink;
        $agent->office_phone = $request->officePhone;
        $agent->fax = $request->fax;
        $agent->description = $request->description;

        return $agent->save();
    }

    public function deleteAgent($request){

        $agent = $this->getAgent($request->id);

        if(!empty($agent)){
            return $agent->delete();
        }
    }

    public function deleteAgentImages($request){

        $agent = $this->getAgent($request->id);

        if($request->name == 'logo'){

            $path = $agent->logo_path;
        }

        if($request->name == 'images'){

            $image = $agent->photo;

            $path = $agent->photo_url;
        }

        if(Storage::disk('s3')->exists($path)) {
            Storage::disk('s3')->delete($path);
        }

        $image = '';

        $path = '';

        if($request->name == 'logo'){

            $agent->logo_path = $path;
        }

        if($request->name == 'images'){

            $agent->photo = $image;
            $agent->photo_url = $path;
        }

        return $agent->save();
    }

    public function getCurrentAgent($id)
    {
        return AgentInfo::where('user_id','=',$id)->with('user')->get();
    }

    public function getListingAgents($buidingId)
    {
        return AgentsListings::where(['listings_id' => $buidingId])->where('agent_id','>', 0)->join('agent_infos', 'agent_id', '=', 'agent_infos.external_id')->get();

    }

    public function getBuildingAgents($id)
    {
        return AgentsBuildings::where(['building_id' => $id])->where('agent_id','>',0)->join('agent_infos', 'agent_id', '=', 'agent_infos.external_id')->get();

    }

    public function getAgentEstates($id){

        $estates['sales'] = array();
        $estates['rentals'] = array();

        $agent = AgentInfo::where('id', $id)->get();

        $user_id = $agent[0]->user_id;

        if($user_id == 0){

            $listings = AgentsListings::join('agent_infos', 'agent_id', '=', 'agent_infos.external_id')->where('agent_infos.id', '=', $id)->get();

            foreach ($listings as $listing){               

                $sale = EstateInfo::where('listing_id', '=', $listing['listings_id'])
                                    ->where('active', '=', '1')
                                    ->get();

                $images = array();
                if (isset($sale[0])) {
                    $images = $sale[0]->path_for_images;
                    $images = json_decode($images);
                }

                if (count($images)){
                    $img = array_shift($images); //use first images
                    if (!empty($img)){ 
                        if ($sale[0]->estate_type==2){
                            if ($sale[0]->amazon_id == 0)
                                $sale[0]->img =  env('S3_IMG_PATH').'images-rental/'.$img;
                            elseif ($sale[0]->amazon_id == 1)
                                $sale[0]->img =  env('S3_IMG_PATH_1').$img;
                        }
                        elseif ($sale[0]->estate_type==1){
                            if ($sale[0]->amazon_id == 0)
                                $sale[0]->img =  env('S3_IMG_PATH').'sales-listing-images/'.$img;
                            elseif ($sale[0]->amazon_id == 1)    
                                $sale[0]->img = env('S3_IMG_PATH_1').$img;
                        }
                    }
                }else{
                    if (isset($sale[0]))
                        $sale[0]->img = "/images/default_image_coming.jpg"; // not found img
                }

                if(isset($sale[0]) && !empty($sale[0])){

                    if($sale[0]['estate_type'] == '1'){
                        $estates['sales'][] = $sale[0];
                    }

                    if($sale[0]['estate_type'] == '2'){
                        $estates['rentals'][] = $sale[0];
                    }
                }

            }

            //print_r($estates); exit;

        } else {

            $results = EstateInfo::where('user_id', $user_id)
                                 ->where('active', '=', '1')
                                 ->get();            

            foreach ($results as $result){
                $images = $result->path_for_images;
                $images = json_decode($images);

                if (count($images)){
                    $img = array_shift($images); //use first images
                    if (!empty($img)){
                        if ($result->estate_type==2){
                            if ($result->amazon_id == 0)
                                $result->img =  env('S3_IMG_PATH').'images-rental/'.$img;
                            elseif ($result->amazon_id == 1)
                                $result->img =  env('S3_IMG_PATH_1').$img;
                        }
                        elseif ($result->estate_type==1){
                            if ($result->amazon_id == 0)
                                $result->img =  env('S3_IMG_PATH').'sales-listing-images/'.$img;
                            elseif ($result->amazon_id == 1)    
                                $result->img = env('S3_IMG_PATH_1').$img;
                        }
                    }
                }else{
                    $result->img = "/images/default_image_coming.jpg"; // not found img
                }

                if($result['estate_type'] == '1'){
                    $estates['sales'][] = $result;
                }

                if($result['estate_type'] == '2'){
                    $estates['rentals'][] = $result;
                }
            }

        }

        return $estates;
    }

    public function searchAgents($search){

        $agents = AgentInfo::where('full_name', 'like', '%' . $search . '%')
                            ->orWhere(DB::raw('CONCAT(first_name, " " ,last_name)'), 'like', '%' . $search . '%')
                            ->paginate(env('SEARCH_RESULTS_PER_PAGE'))
                            ->appends(request()->query());

        foreach ($agents as $agent){
            if (strlen($agent->photo) && $agent->photo != 'default/agent_no_photo_64x64.png'){

                if ($agent->user_id !== 0){
                    $agent->img = env('S3_IMG_PATH_1') . $agent->photo_url;
                } else {
                    $agent->img = env('S3_IMG_PATH') . 'images-rental/' . $agent->photo;
                }

            } else {
                $agent->img = '/images/default_agent.jpg';
            }

            if(!$agent->full_name){
                $agent->full_name = $agent->first_name . ' ' . $agent->last_name;
            }
        }

        return $agents;
    }


    public function autocomplete($search){

        $agents =  AgentInfo::select('id', 'last_name', 'first_name', 'full_name')
                        ->where('full_name', 'like', '%' . $search . '%')
                        ->orWhere(DB::raw('CONCAT(first_name, " " ,last_name)'), 'like', '%' . $search . '%')
                        ->limit(5)
                        ->get();

        $result = array();
        foreach ($agents as $agent){
            if ($agent->full_name) {
                $name1 = $agent->full_name;
                $name2 = str_replace(' ', '_', $agent->full_name);
                
            }
            else {
                $name1 = $agent->first_name . ' ' . $agent->last_name;
                $name2 = str_replace(' ', '_', $agent->first_name) . '_'. str_replace(' ', '_', $agent->last_name);
            }

            $result[] = array(
                'title' => $name1,
                'link'  => '/agent/' . $name2 . '/' . $agent->id
            );
        }

        return $result;
    }

}