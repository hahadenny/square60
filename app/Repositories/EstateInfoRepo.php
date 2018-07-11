<?php

namespace App\Repositories;

use App\EstateInfo;
use App\EstateFilters;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\AgentInfo;
use Illuminate\Support\Facades\DB;

class EstateInfoRepo
{
    private $open;
    private $filter;

    /**
     * EstateInfoRepo constructor.
     * @param $open
     */
    public function __construct(OpenHouseRepo $open, FilterRepo $filter)
    {
        $this->open = $open;
        $this->filter = $filter;
    }

    public function getUsersActiveListingOpenHouse($id)
    {
        return EstateInfo::where('user_id', $id)
                ->where('active', 1)
                ->with('openHouse')->get();
    }

    public function queryEstate()
    {
        return EstateInfo::query();
    }

    public function getListings($type) 
    {
        return EstateInfo::where('estate_type','=',$type)->paginate(env('SEARCH_RESULTS_PER_PAGE'));
    }

    public function getListing($id)
    {
        return EstateInfo::with('logo')->with('images')->find($id);
    }

    public function incrementListing($object)
    {
        return $object->increment('views_count');
    }

    public function currentAvailebleListing($estate_type, $building_id, $sale_id)
    {
        return EstateInfo::where('estate_type', '=', $estate_type)
                        ->where('id', '!=', $sale_id)
                        ->where('building_id', '=', $building_id)
                        ->where('active', '=', 1)
                        ->take(5)
                        ->get();
    }

    public function getRelatedBuilding($id)
    {
        return EstateInfo::where('building_id',$id )->first();
    }

    public function getBuildingListings($building_id, $estate_type)
    {
        return EstateInfo::where('building_id', $building_id)
                        ->where('estate_type', $estate_type)
                        ->where('active', '=', 1)
                        ->take(5)
                        ->with('openHouse')
                        ->get();
    }

    protected function getFeatureListings($user_id, $estate_type, $feature)
    {
        /*echo EstateInfo::where('user_id','=',$user_id)
        ->where('estate_type','=', $estate_type)
        ->where('feature','=', $feature)
        ->with('openHouse')->toSql();
        exit;*/

        $features = EstateInfo::where('user_id','=',$user_id)
                        ->where('estate_type','=', $estate_type)
                        ->where('feature','=', $feature)
                        ->with(['openHouse'])
                        ->with(array('features' => function($query) {
                            $query->whereNotIn('status', ['renew_failed', 'ended', 'renewed'])->orderBy('renew', 'DESC')->orderBy('ends_at', 'DESC');
                        }))
                        ->with(array('premiums' => function($query) {
                            $query->whereNotIn('status', ['renew_failed', 'ended', 'renewed'])->orderBy('renew', 'DESC')->orderBy('ends_at', 'DESC');
                        }))
                        ->get();

        return $features;
    }

    public function showListing($user_id, $type)
    {
        $data = $this->getFeatureListings($user_id, $type, 0);
        //print_r($data); exit;

        $count = 0;
        if($data){
            $count = count($data);
            //echo $count; exit;
        }

        $this->prepareListingImages($data);

        $feature = $this->getFeatureListings($user_id, $type, 1);
        //print_r($feature);exit;

        if($feature){
            $countFeature = count($feature);
        }

        $this->prepareListingImages($feature);

        return compact('data','count','feature','countFeature');
    }

    protected function prepareListingImages($imagesArray)
    { 
        foreach ($imagesArray as $item){ 
            $images = json_decode($item->path_for_images, true);
            if(count($images)){
                $img =array_shift($images);
                $item->img = $img;
            }else{
                $item->img = 'unit_images/137823/10236523/299542512.jpg';
            }
        }
    }

    public function findListing($id)
    {
        return EstateInfo::find($id);
    }

    public function newListing()
    {
        return new EstateInfo();
    }

    public function addSellListing($request, $estate)
    {
        $imagesSave = $this->saveFiles($request->image, $estate->images, $estate->path_for_images, 'sales-listing-images/unit-images/', $request->user()->id);

        $images= $imagesSave['files'];
        $path_for_images = $imagesSave['path_for_files'];

        $plansSave = $this->saveFiles($request->plans, $estate->floor_plan, $estate->path_for_floorplans, 'sales-listing-images/floorplan-images/', $request->user()->id);

        $plans = $plansSave['files'];
        $path_for_plans = $plansSave['path_for_files'];

        $agreementSave = $this->saveFiles($request->agreement, $estate->ex_agreement, $estate->path_for_ex_agreement, 'sales-listing-images/document-images/', $request->user()->id);

        $agreement = $agreementSave['files'];
        $path_for_agreement = $agreementSave['path_for_files'];

        $copylicenseSave = $this->saveFiles($request->copylicense, $estate->copy_licence, $estate->path_for_copy_licence, 'sales-listing-images/document-images/', $request->user()->id);

        $copyLicense = $copylicenseSave['files'];
        $path_for_license = $copylicenseSave['path_for_files'];

        if (Auth::user()->isOwner()) {
            $photoidSave = $this->saveFiles($request->photoid, $estate->photo_id, $estate->path_for_photo_id, 'sales-listing-images/document-images/', $request->user()->id);

            $photoID = $photoidSave['files'];
            $path_for_photo_id = $photoidSave['path_for_files'];
        }

        $utilityBillSave = $this->saveFiles($request->utilitybill, $estate->un_bill, $estate->path_for_un_bill, 'sales-listing-images/document-images/', $request->user()->id);

        $utilityBill = $utilityBillSave['files'];
        $path_for_bill = $utilityBillSave['path_for_files'];

        $deedSave = $this->saveFiles($request->deed, $estate->deed, $estate->path_for_deed, 'sales-listing-images/document-images/', $request->user()->id);

        $deed = $deedSave['files'];
        $path_for_deed = $deedSave['path_for_files'];

        $filters = $this->filter->prepareSaveListingFilters($request->type, $request->district, $request->filters);

        $type = $filters['type'];

        $features_list = $filters['features_list'];

        $amenities_list = $filters['amenities_list'];

        $neighborhood = $filters['neighborhood'];

        if (Auth::user()->isAdmin()){
            $user_id = $estate->user_id;
        }else{
            $user_id = $request->user_id;
        }

        $agentInfo = AgentInfo::where('user_id',$request->user_id)->get();

        if(!empty($agentInfo[0])){
            $agentCompany = $agentInfo[0]->company;
            $agentPhone = $agentInfo[0]->office_phone;
            $agentLogo = $agentInfo[0]->logo_path;
        }else{
            $agentCompany = '';
            $agentPhone = '';
            $agentLogo = '';
        }

        $amenities = implode ( ',',$amenities_list);
        $features = implode ( ',',$features_list);

        if(is_numeric($request->address)) {
            $buildindId = $request->address;
        } else {
            $buildindId = '0';
        }

        if(isset($request->charge)){
            $charge = $request->charge;
        }else{
            $charge = 0;
        }

        if(isset($request->maintenance)){
            $maintenance = $request->maintenance;
        }else{
            $maintenance = 0;
        }

        if(isset($request->tax)){
            $tax = $request->tax;
        }else{
            $tax = 0;
        }

        if($estate->feature){
            $feature = $estate->feature;
        }else{
            $feature = 0;
        }

        if ($request->active)
            $estate->active = $request->active;
        else
            $estate->active = 0;

        $estate->estate_type = 1;
        $estate->name = $request->street_address;
        $estate->full_address = $request->street_address;
        $estate->address = $request->street_address;
        $estate->city = $request->city;
        $estate->state = $request->ny;
        $estate->zip = $request->zip;
        $estate->images = $images;
        $estate->units = 0;
        $estate->stories = '';
        $estate->year_built = $request->year_built;
        $estate->building_type = $type;
        $estate->type_id = $request->type;
        $estate->unit_type = $type;
        $estate->neighborhood = $neighborhood;
        $estate->district_id = $request->district;
        $estate->amenities = $features;
        $estate->b_amenities = $amenities;
        $estate->date = date('Y-m-d');
        $estate->unit = $request->apartment;
        if (!$estate->unit)
            $estate->unit = '';
        $estate->price = $request->price;
        if (!$request->last_price)
            $request->last_price = $request->price;
        $estate->last_price = $request->last_price;
        $estate->beds = $request->bed;
        if (!$estate->beds)
            $estate->beds = 0;
        $estate->baths = $request->bath;
        if (!$estate->baths)
            $estate->baths = 0;
        $estate->sq_feet = $request->size;
        if (!$estate->sq_feet)
            $estate->sq_feet = 0;
        $estate->common_charges = $charge;
        $estate->monthly_taxes = $tax;
        $estate->maintenance = $maintenance;
        $estate->agent_company = $agentCompany;
        $estate->agent_phone = $agentPhone;
        $estate->logo_path = $agentLogo;
        $estate->unit_images = $images;
        $estate->unit_description = $request->description;
        $estate->building_id = $buildindId;
        $estate->views_count = '0';
        $estate->path_for_images = $path_for_images;
        $estate->path_for_floorplans = $path_for_plans;
        $estate->user_id = $user_id;
        $estate->apartment = $request->apartment;
        $estate->boro = $request->boro;
        $estate->room = $request->room;
        $estate->web = $request->web;
        $estate->commission = $request->commission;
        $estate->feature = $feature;
        $estate->broker = $request->broker;
        $estate->floor_plan = $plans;
        $estate->ex_agreement = $agreement;
        $estate->copy_licence = $copyLicense;
        if (isset($photoID) && $photoID)
            $estate->photo_id = $photoID;
        $estate->un_bill = $utilityBill;
        $estate->deed = $deed;
        $estate->path_for_ex_agreement = $path_for_agreement;
        $estate->path_for_copy_licence = $path_for_license;
        if (isset($path_for_photo_id) && $path_for_photo_id)
            $estate->path_for_photo_id = $path_for_photo_id;
        $estate->path_for_un_bill = $path_for_bill;
        $estate->path_for_deed = $path_for_deed;
        $estate->amazon_id = 1;
        $estate->listing_id = time();
        $estate->condition = $request->condition;

        if($estate->save()){
            $estateId = $estate->id;

            $saveFilters = $this->saveFilters($estateId,$request->filters, $request->model);

            $attachedBuilding = $this->attachedBuilding($estate->listing_id,$buildindId, $request->model);

            if(isset($request->openHouse) && !empty($request->openHouse))
            {
                $openHouse = $this->open->saveOpenHouse($estateId, $request->openHouse);
            }
            return true;
        }else{
            return false;
        }


    }

    public function addRentalListing($request, $estate)
    {
        $imagesSave = $this->saveFiles($request->image, $estate->images, $estate->path_for_images, 'images-rental/unit-images/', $request->user()->id);

        $images= $imagesSave['files'];
        $path_for_images = $imagesSave['path_for_files'];

        $plansSave = $this->saveFiles($request->plans, $estate->floor_plan, $estate->path_for_floorplans, 'images-rental/floorplan-images/', $request->user()->id);

        $plans = $plansSave['files'];
        $path_for_plans = $plansSave['path_for_files'];

        $agreementSave = $this->saveFiles($request->agreement, $estate->ex_agreement, $estate->path_for_ex_agreement, 'sales-listing-images/document-images/', $request->user()->id);

        $agreement = $agreementSave['files'];
        $path_for_agreement = $agreementSave['path_for_files'];

        $copylicenseSave = $this->saveFiles($request->copylicense, $estate->copy_licence, $estate->path_for_copy_licence, 'images-rental/document-images/', $request->user()->id);

        $copyLicense = $copylicenseSave['files'];
        $path_for_license = $copylicenseSave['path_for_files'];

        if (Auth::user()->isOwner()) {
            $photoidSave = $this->saveFiles($request->photoid, $estate->photo_id, $estate->path_for_photo_id, 'sales-listing-images/document-images/', $request->user()->id);

            $photoID = $photoidSave['files'];
            $path_for_photo_id = $photoidSave['path_for_files'];
        }

        $utilityBillSave = $this->saveFiles($request->utilitybill, $estate->un_bill, $estate->path_for_un_bill, 'images-rental/document-images/', $request->user()->id);

        $utilityBill = $utilityBillSave['files'];
        $path_for_bill = $utilityBillSave['path_for_files'];

        $deedSave = $this->saveFiles($request->deed, $estate->deed, $estate->path_for_deed, 'images-rental/document-images/', $request->user()->id);

        $deed = $deedSave['files'];
        $path_for_deed = $deedSave['path_for_files'];


        $filters = $this->filter->prepareSaveListingFilters($request->type, $request->district, $request->filters);

        $type = $filters['type'];

        $features_list = $filters['features_list'];

        $amenities_list = $filters['amenities_list'];

        $neighborhood = $filters['neighborhood'];

        if (Auth::user()->isAdmin()){
            $user_id = $estate->user_id;
        }else{
            $user_id = $request->user_id;
        }

        $agentInfo = AgentInfo::where('user_id',$request->user_id)->get();

        if(!empty($agentInfo[0])){
            $agentCompany = $agentInfo[0]->company;
            $agentPhone = $agentInfo[0]->office_phone;
            $agentLogo = $agentInfo[0]->logo_path;
        }else{
            $agentCompany = '';
            $agentPhone = '';
            $agentLogo = '';
        }

        $amenities = implode ( ',',$amenities_list);
        $features = implode ( ',',$features_list);

        if(is_numeric($request->address)) {
            $buildindId = $request->address;
        } else {
            $buildindId = '0';
        }

        if($estate->feature){
            $feature = $estate->feature;
        }else{
            $feature = 0;
        }

        if ($request->active)
            $estate->active = $request->active;
        else
            $estate->active = 0;

        $estate->estate_type = 2;
        $estate->name = $request->street_address;
        $estate->full_address = $request->street_address;
        $estate->address = $request->street_address;
        $estate->city = $request->city;
        $estate->state = $request->ny;
        $estate->zip = $request->zip;
        $estate->images = $images;
        $estate->units = 0;
        $estate->stories = '';
        $estate->year_built = $request->year_built;
        $estate->building_type = $type;
        $estate->type_id = $request->type;
        $estate->unit_type = $type;
        $estate->neighborhood = $neighborhood;
        $estate->district_id = $request->district;
        $estate->amenities = $features;
        $estate->b_amenities = $amenities;
        $estate->date = date('Y-m-d');
        $estate->unit = $request->apartment;
        if (!$estate->unit)
            $estate->unit = '';
        $estate->price = $request->price;
        if (!$request->last_price)
            $request->last_price = $request->price;
        $estate->last_price = $request->last_price;
        $estate->beds = $request->bed;        
        if (!$estate->beds)
            $estate->beds = 0;
        $estate->baths = $request->bath;
        if (!$estate->baths)
            $estate->baths = 0;
        $estate->sq_feet = $request->size ? $request->size : 0;
        $estate->common_charges = 0;
        $estate->monthly_taxes = 0;
        $estate->maintenance = 0;
        $estate->agent_company = $agentCompany;
        $estate->agent_phone = $agentPhone;
        $estate->logo_path = $agentLogo;
        $estate->unit_images = $images;
        $estate->unit_description = $request->description;
        $estate->building_id = $buildindId;
        $estate->views_count = '0';
        $estate->path_for_images = $path_for_images;
        $estate->path_for_floorplans = $path_for_plans;
        $estate->user_id = $user_id;
        $estate->apartment = $request->apartment;
        $estate->boro = $request->boro;
        $estate->room = $request->room;
        $estate->web = $request->web;
        $estate->commission = $request->commission;
        $estate->feature = $feature;
        $estate->broker = $request->broker;
        $estate->floor_plan = $plans;
        $estate->ex_agreement = $agreement;
        $estate->copy_licence = $copyLicense;
        if (isset($photoID) && $photoID)
            $estate->photo_id = $photoID;
        $estate->un_bill = $utilityBill;
        $estate->deed = $deed;
        $estate->path_for_ex_agreement = $path_for_agreement;
        $estate->path_for_copy_licence = $path_for_license;
        if (isset($path_for_photo_id) && $path_for_photo_id)
            $estate->path_for_photo_id = $path_for_photo_id;
        $estate->path_for_un_bill = $path_for_bill;
        $estate->path_for_deed = $path_for_deed;
        $estate->amazon_id = 1;
        $estate->listing_id = time();
        $estate->fees = $request->fees;
        $estate->condition = $request->condition;

        if($estate->save()){
            $estateId = $estate->id;

            $saveFilters = $this->saveFilters($estateId,$request->filters, $request->model);

            $attachedBuilding = $this->attachedBuilding($estate->listing_id,$buildindId, $request->model);

            if(isset($request->openHouse) && !empty($request->openHouse))
            {
                $openHouse = $this->open->saveOpenHouse($estateId, $request->openHouse);
            }
            return true;
        }else{
            return false;
        }


    }

    protected function saveFiles($file, $existFile, $filePath, $folder, $user_id){

        if($file){

            $filesData = $this->uploadFiles($file, $user_id, $folder);

            $fileNames = $filesData['fileNames'];
            $filePaths = $filesData['filePaths'];

            if(!empty($existFile)){

                $filesArray = @json_decode($existFile, true);
                $files = array_merge($filesArray, $fileNames);
                $files = \GuzzleHttp\json_encode($files);

                $pathArray = @json_decode($filePath, true);
                $path = array_merge($pathArray, $filePaths);
                $path_for_files = \GuzzleHttp\json_encode($path);
            }else{
                $files = \GuzzleHttp\json_encode($fileNames);

                $path_for_files = \GuzzleHttp\json_encode($filePaths);
            }
        }else{
            if(empty($filePath)){
                $files = '';
                $path_for_files = '';
            }else{
                $files= $existFile;

                $path_for_files = $filePath;
            }
        }

        return compact('files', 'path_for_files');
    }


    protected function uploadFiles($files, $userId, $folder){

        $fileNames = array();
        $filePaths = array();
        if(!empty($files)){

            foreach ($files as $item) {

                $newFileName = uniqid(time()). '.' . $item->getClientOriginalExtension();

                $filePath = $folder.$userId.'/' . $newFileName;

                $s3 = Storage::disk('s3');

                $s3->put($filePath, file_get_contents($item), 'public');

                $fileNames[] = $newFileName;
                $filePaths[] = $filePath;

            }

            return compact('fileNames', 'filePaths');
        }
    }

    protected function saveFilters($estateId,$data,$update){

        if($update == 1){
            $estateFilters = EstateFilters::find($estateId);
        }else{
            $estateFilters = new EstateFilters();
        }

        $estateFilters->estate_id = $estateId;

        foreach ($data as $item){

            if ($item == 354){
                $estateFilters->f_354 = 1;
            }
            if ($item == 356){
                $estateFilters->f_356 = 1;
            }
            if ($item == 357){
                $estateFilters->f_357 = 1;
            }
            if ($item == 361){
                $estateFilters->f_361 = 1;
            }
            if ($item == 362){
                $estateFilters->f_362 = 1;
            }
            if ($item == 363){
                $estateFilters->f_363 = 1;
            }
            if ($item == 365){
                $estateFilters->f_365 = 1;
            }
            if ($item == 367){
                $estateFilters->f_367 = 1;
            }
            if ($item == 379){
                $estateFilters->f_379 = 1;
            }
            if ($item == 380){
                $estateFilters->f_380 = 1;
            }
            if ($item == 381){
                $estateFilters->f_381 = 1;
            }
            if ($item == 395){
                $estateFilters->f_395 = 1;
            }
            if ($item == 394){
                $estateFilters->f_394 = 1;
            }
            if ($item == 393){
                $estateFilters->f_393 = 1;
            }
            if ($item == 392){
                $estateFilters->f_392 = 1;
            }
            if ($item == 391){
                $estateFilters->f_391 = 1;
            }
            if ($item == 390){
                $estateFilters->f_390 = 1;
            }
            if ($item == 389){
                $estateFilters->f_389 = 1;
            }
            if ($item == 388){
                $estateFilters->f_388 = 1;
            }
            if ($item == 387){
                $estateFilters->f_387 = 1;
            }
            if ($item == 386){
                $estateFilters->f_386 = 1;
            }
            if ($item == 385){
                $estateFilters->f_385 = 1;
            }
            if ($item == 384){
                $estateFilters->f_384 = 1;
            }
        }
        return $estateFilters->save();
    }

    protected function attachedBuilding($listing_id, $building_id, $update)
    {
        if($update == 1){
            return $listindBilding = DB::table('buildings_listings')
                ->where('listing_id', $listing_id)
                ->where('building_id', $building_id)
                ->update(['listing_id' => $listing_id, 'building_id' => $building_id]);
        }else{
            return $listindBilding = DB::table('buildings_listings')
                ->insert(['listing_id' => $listing_id, 'building_id' => $building_id]);
        }
    }

    public function submitListing($request){

        $list = $this->findListing($request->id);

        if (!$list->is_verified)
            return 'List is not approved yet.';

        if(strtolower($request->submit) == 'non-active' && $list->active == 0){

            $list->active = 1;

            if($list->save()){
               return 'List activated.';
            }

        }elseif(strtolower($request->submit) == 'active' && $list->active == 1){

            $list->active = 0;

            if($list->save()){
                return 'List disabled.';
            }
        }
    }

    public function editListing($request)
    {
        $list = $this->findListing($request->id);

        $futures_array = explode(",", $list->amenities);
        $amenities_array = explode(",", $list->b_amenities);

        $data = $this->filter->prepareEditListingData($amenities_array, $futures_array);

        $amenities = $data->pluck('filter_data_id')->all();

        $images = @json_decode($list->images);
        $path_for_images = @json_decode($list->path_for_images);

        $plans = @json_decode($list->floor_plan);
        $path_for_plans = @json_decode($list->path_for_floorplans);

        $ex_agreement = @json_decode($list->ex_agreement);
        $path_for_ex_agreement = @json_decode($list->path_for_ex_agreement);

        $copy_licence = @json_decode($list->copy_licence);
        $path_for_copy_licence = @json_decode($list->path_for_copy_licence);

        $photo_id = @json_decode($list->photo_id);
        $path_for_photo_id = @json_decode($list->path_for_photo_id);

        $un_bill = @json_decode($list->un_bill);
        $path_for_un_bill = @json_decode($list->path_for_un_bill);

        $deed = @json_decode($list->deed);
        $path_for_deed = @json_decode($list->path_for_deed);

        $list->images = $images;
        $list->path_for_images = $path_for_images;
        $list->floor_plan = $plans;
        $list->path_for_floorplans = $path_for_plans;
        $list->ex_agreement = $ex_agreement;
        $list->path_for_ex_agreement = $path_for_ex_agreement;
        $list->copy_licence = $copy_licence;
        $list->photo_id = $photo_id;
        $list->path_for_copy_licence = $path_for_copy_licence;
        $list->path_for_photo_id = $path_for_photo_id;
        $list->ex_agreement = $un_bill;
        $list->path_for_un_bill = $path_for_un_bill;
        $list->deed = $deed;
        $list->path_for_deed = $path_for_deed;
        $list->amenities = $amenities;

        $list->beds = (int)$list->beds;
        $list->baths = (int)$list->baths;
        $list->sq_feet = (int)$list->sq_feet;

        return $list;
    }

    public function deleteListing($id)
    {
        if(!empty($id)){

            $list = $this->findListing($id);

            if(!empty($list)){
                $estateFilters = EstateFilters::find($id);

                $attachedBuilding = DB::table('buildings_listings')
                    ->where('listing_id', $id);

                $deleteListing = $list->delete();

                if($deleteListing)
                {
                    $estateFilters->delete();

                    $attachedBuilding->delete();

                    return $deleteListing;
                }
            }

        }else{
            return false;
        }
    }

    public function deleteImages($request)
    {
        $list = $this->findListing($request->id);

        if($request->name == 'images'){

            $images = @json_decode($list->images);

            $path = @json_decode($list->path_for_images);

            foreach ($images as $k => $image) {
                if ($image == $request->img_name) {
                    if(Storage::disk('s3')->exists($path[$k])) {
                        Storage::disk('s3')->delete($path[$k]);
                    }
    
                    unset($images[$k]);
                    unset($path[$k]);
                }
            }

            $images = array_values($images);
            $path = array_values($path);

            $path_for_images = \GuzzleHttp\json_encode($path);
            $images_list = \GuzzleHttp\json_encode($images);

            $list->images = $images_list;
            $list->path_for_images = $path_for_images;

        }elseif($request->name == 'plans'){

            $plans = @json_decode($list->floor_plan);

            $path = @json_decode($list->path_for_floorplans);

            foreach ($plans as $k => $image) {
                if ($image == $request->img_name) {
                    if(Storage::disk('s3')->exists($path[$k])) {
                        Storage::disk('s3')->delete($path[$k]);
                    }
    
                    unset($plans[$k]);
                    unset($path[$k]);
                }
            }

            $plans = array_values($plans);
            $path = array_values($path);

            $path_for_floor_plan = \GuzzleHttp\json_encode($path);

            $plans_list = \GuzzleHttp\json_encode($plans);

            $list->floor_plan = $plans_list;
            $list->path_for_floorplans = $path_for_floor_plan;

        }elseif($request->name == 'agreement'){

            $agreement = @json_decode($list->ex_agreement, true);

            $path = @json_decode($list->path_for_ex_agreement, true);

            foreach ($agreement as $k => $image) {
                if ($image == $request->img_name) {
                    if(Storage::disk('s3')->exists($path[$k])) {
                        Storage::disk('s3')->delete($path[$k]);
                    }
    
                    unset($agreement[$k]);
                    unset($path[$k]);
                }
            }

            $agreement = array_values($agreement);
            $path = array_values($path);

            $path_for_ex_agreement = \GuzzleHttp\json_encode($path);

            $agreement_list = \GuzzleHttp\json_encode($agreement);

            $list->ex_agreement = $agreement_list;
            $list->path_for_ex_agreement = $path_for_ex_agreement;

        }elseif($request->name == 'licence'){

            $licence = @json_decode($list->copy_licence);

            $path = @json_decode($list->path_for_copy_licence);

            foreach ($licence as $k => $image) {
                if ($image == $request->img_name) {
                    if(Storage::disk('s3')->exists($path[$k])) {
                        Storage::disk('s3')->delete($path[$k]);
                    }
    
                    unset($licence[$k]);
                    unset($path[$k]);
                }
            }

            $licence = array_values($licence);
            $path = array_values($path);

            $path_for_copy_licence = \GuzzleHttp\json_encode($path);

            $licence_list = \GuzzleHttp\json_encode($licence);

            $list->copy_licence = $licence_list;
            $list->path_for_copy_licence = $path_for_copy_licence;

        }elseif($request->name == 'photoid'){

            $photo_id = @json_decode($list->photo_id);

            $path = @json_decode($list->path_for_photo_id);

            foreach ($photo_id as $k => $image) {
                if ($image == $request->img_name) {
                    if(Storage::disk('s3')->exists($path[$k])) {
                        Storage::disk('s3')->delete($path[$k]);
                    }
    
                    unset($photo_id[$k]);
                    unset($path[$k]);
                }
            }

            $photo_id = array_values($photo_id);
            $path = array_values($path);

            $path_for_photo_id = \GuzzleHttp\json_encode($path);

            $photo_id_list = \GuzzleHttp\json_encode($photo_id);

            $list->photo_id = $photo_id_list;
            $list->path_for_photo_id = $path_for_photo_id;

        }elseif($request->name == 'deed'){

            $deed = @json_decode($list->deed);

            $path = @json_decode($list->path_for_deed);

            foreach ($deed as $k => $image) {
                if ($image == $request->img_name) {
                    if(Storage::disk('s3')->exists($path[$k])) {
                        Storage::disk('s3')->delete($path[$k]);
                    }
    
                    unset($deed[$k]);
                    unset($path[$k]);
                }
            }

            $deed = array_values($deed);
            $path = array_values($path);

            $path_for_deed = \GuzzleHttp\json_encode($path);

            $deed_list = \GuzzleHttp\json_encode($deed);

            $list->deed = $deed_list;
            $list->path_for_deed = $path_for_deed;

        }elseif($request->name == 'bill'){

            $bill = @json_decode($list->un_bill);

            $path = @json_decode($list->path_for_un_bill);

            foreach ($bill as $k => $image) {
                if ($image == $request->img_name) {
                    if(Storage::disk('s3')->exists($path[$k])) {
                        Storage::disk('s3')->delete($path[$k]);
                    }
    
                    unset($bill[$k]);
                    unset($path[$k]);
                }
            }

            $bill = array_values($bill);
            $path = array_values($path);

            $path_for_bill = \GuzzleHttp\json_encode($path);

            $bill_list = \GuzzleHttp\json_encode($bill);

            $list->un_bill = $bill_list;
            $list->path_for_un_bill = $path_for_bill;
        }

        return $list->save();
    }





}