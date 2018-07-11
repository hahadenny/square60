<?php

namespace App\Repositories;

use App\Building;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class BuildingRepo
{
    public function getBuildings()
    {
        return Building::all();
    }

    public function getBuilding($name, $city)
    {
        //return Building::find($id);
        $name = str_replace('_', ' ', $name);
        $city = str_replace('_', ' ', $city);
        $result = Building::where('building_name', $name)->where('building_city', $city)->with('filterType')->get()->first();

        if ($result->described) {
            $label = DB::table('name_label')->select('user_id')->where('building_id', '=', $result->building_id)->where('type', 'desc')->where('status', 'success')->orderBy('renew', 'DESC')->orderBy('ends_at', 'DESC')->get()->first();
            $agent = DB::table('agent_infos')->select('full_name', 'company')->where('user_id', $label->user_id)->get()->first();
            $result->desc_name = $agent->full_name;
            $result->desc_company = $agent->company;
        }

        return $result;
    }

    public function getBuildingById($id)
    {
        return Building::find($id);
    }

    public function getBuildingByBuildingId($id)
    {
        return Building::where('building_id', $id)->get()->first();
    }

    public function buildingsList()
    {
        return Building::paginate(env('SEARCH_RESULTS_PER_PAGE'));
    }

    public function getWithoutNameLabelBuildings()
    {
        return Building::where('name_label', 0)->orWhere('described', 0)->get();
    }

    public function getCurrentNameLabelBuildings($userid)
    {
        $data = DB::table('name_label')
                    ->distinct()
                    ->whereNotIn('status', ['renew_failed', 'ended', 'renewed'])
                    ->where('user_id', '=', $userid)
                    ->where('ends_at', '>', Carbon::now())
                    ->get(['building_id', 'renew', 'ends_at', 'type']);
            
        $cur_buildingIds = array();
        $cur_renew = array();
        $cur_ends_at = array();
        $cur_type = array();
        foreach($data as $d) {
            $cur_buildingIds[] = $d->building_id;
            $cur_renew[$d->building_id.'_'.$d->type] = $d->renew;
            $cur_ends_at[$d->building_id.'_'.$d->type] = $d->ends_at;
        }

        $bdata = Building::whereIn('building_id', $cur_buildingIds)->get();

        foreach ($bdata as $k => $bd) {
            $bdata[$k]->img_ends_at = isset($cur_ends_at[$bd->building_id.'_img']) ? $cur_ends_at[$bd->building_id.'_img'] : '';
            $bdata[$k]->desc_ends_at = isset($cur_ends_at[$bd->building_id.'_desc']) ? $cur_ends_at[$bd->building_id.'_desc'] : '';
            $bdata[$k]->img_renew = isset($cur_renew[$bd->building_id.'_img']) ? $cur_renew[$bd->building_id.'_img'] : '';
            $bdata[$k]->desc_renew = isset($cur_renew[$bd->building_id.'_desc']) ? $cur_renew[$bd->building_id.'_desc'] : '';
            $bdata[$k]->building_description = $bd->building_description;

            $path_for_name_label_image = $bd->path_for_name_label_image;
            $path_for_name_label_image = @json_decode($path_for_name_label_image);

            if ($path_for_name_label_image){
                foreach ($path_for_name_label_image as $key=>$row){
                    if (!empty($row) && strlen($row)){                    
                        $path_for_name_label_image[$key] = env('S3_IMG_PATH_1').$row;                    
                    }else {
                        unset($path_for_name_label_image[$key]);
                    }
                }
            }else {
                $path_for_name_label_image = array();
            }  

            $bdata[$k]->name_label_image_path = $path_for_name_label_image;
        }        

        return $bdata;
    }

    public function addBuilding($request, $dataFilters)
    {
        $building = new Building();

        if($request->hasFile('buildingImages')){

            $imagesData = $this->uploadImages($request->building_images, $request->user()->id);

            $fileNames = $imagesData['fileNames'];
            $filePaths = $imagesData['filePaths'];

            if(!empty($building->building_images)){

                $imagesArray = @json_decode($building->building_images);
                $images = array_merge($imagesArray, $fileNames);
                $images = \GuzzleHttp\json_encode($images);

                $pathArray = @json_decode($building->path_for_building_images_on_s3);
                $path = array_merge($pathArray, $filePaths);
                $path_for_images = \GuzzleHttp\json_encode($path);
            }else{
                $images = \GuzzleHttp\json_encode($fileNames);

                $path_for_images = \GuzzleHttp\json_encode($filePaths);
            }
        }else{
            if(empty($building->path_for_building_images_on_s3)){
                $images = '';
                $path_for_images = '';
            }else{
                $images= $building->building_images;

                $path_for_images = $building->path_for_building_images_on_s3;
            }
        }

        $amenities_list = array();
        $requestAmenities = $request->buildingAmenities;

        foreach($dataFilters['filters'] as $item){
            if (in_array($item->filter_data_id, $requestAmenities)) {
                $amenities_list[] = $item->value;
            }
        }

        $amenities = implode(',',$amenities_list);

        $building->building_id = $request->buildingID;
        $building->building_address = $request->buildingAddress;
        $building->building_name = $request->buildingName;
        $building->building_city = $request->buildingCity;
        $building->building_state = $request->buildingState;
        $building->building_zip = $request->buildingZip;
        $building->building_units = $request->buildingUnits;
        $building->building_stories = $request->buildingStories;
        $building->building_build_year = $request->buildingBuildYear;
        $building->building_amenities = $amenities;
        $building->building_description = $request->buildingDescription;
        $building->path_for_building_images_on_s3 = $path_for_images;
        $building->building_images = $images;
        $building->b_listing_type = $request->listingType;

        return $building->save();
    }

    protected function uploadImages($images, $userId)
    {
        $fileNames = array();
        $filePaths = array();
        if(!empty($images)){

            foreach ($images as $item) {

                $imageFileName = uniqid(time()). '.' . $item->getClientOriginalExtension();

                $filePath = 'building-images/unit-images/'.$userId.'/' . $imageFileName;

                $s3 = Storage::disk('s3');

                $s3->put($filePath, file_get_contents($item), 'public');

                $fileNames[] = $imageFileName;
                $filePaths[] = $filePath;

            }

            return compact('fileNames', 'filePaths');
        }
    }

    protected function uploadNameLabelImages($images, $userId)
    {
        $fileNames = array();
        $filePaths = array();
        if(!empty($images)){

            foreach ($images as $item) {

                $imageFileName = uniqid(time()). '.' . $item->getClientOriginalExtension();

                $filePath = 'building-images/name-label-images/'.$userId.'/' . $imageFileName;

                $s3 = Storage::disk('s3');

                $s3->put($filePath, file_get_contents($item), 'public');

                $fileNames[] = $imageFileName;
                $filePaths[] = $filePath;

            }

            return compact('fileNames', 'filePaths');
        }
    }

    public function editBuilding($id)
    {
        $list = $this->getBuilding($id);

        if(!empty($list)){

            $amenities_array = explode(",", $list->building_amenities);

            $filters = DB::table('filters_data')
                ->select('filter_data_id')
                ->orWhereIn('value', $amenities_array)
                ->get();

            $amenities = $filters->pluck('filter_data_id')->all();

            $images = @json_decode($list->building_images);
            $path_for_images = @json_decode($list->path_for_building_images_on_s3);

            $list->building_amenities = $amenities;
            $list->path_for_building_images_on_s3 = $path_for_images;
            $list->building_images = $images;

            return $list;
        }
    }

    public function updateBuilding($id, $request, $dataFilters)
    {
        $building = $this->getBuilding($id);

        if($request->hasFile('buildingImages')){

            $imagesData = $this->uploadImages($request->buildingImages, $request->user()->id);

            $fileNames = $imagesData['fileNames'];
            $filePaths = $imagesData['filePaths'];

            if(!empty($building->building_images)){

                $imagesArray = @json_decode($building->building_images);
                $images = array_merge($imagesArray, $fileNames);
                $images = \GuzzleHttp\json_encode($images);

                $pathArray = @json_decode($building->path_for_building_images_on_s3);
                $path = array_merge($pathArray, $filePaths);
                $path_for_images = \GuzzleHttp\json_encode($path);
            }else{
                $images = \GuzzleHttp\json_encode($fileNames);

                $path_for_images = \GuzzleHttp\json_encode($filePaths);
            }
        }else{
            if(empty($building->path_for_building_images_on_s3)){
                $images = '';
                $path_for_images = '';
            }else{
                $images= $building->building_images;

                $path_for_images = $building->path_for_building_images_on_s3;
            }
        }
        $amenities_list = array();
        $requestAmenities = $request->buildingAmenities;

        foreach($dataFilters['filters'] as $item){
            if (in_array($item->filter_data_id, $requestAmenities)) {
                $amenities_list[] = $item->value;
            }
        }

        $amenities = implode(',',$amenities_list);

        $building->building_id = $request->buildingID;
        $building->building_address = $request->buildingAddress;
        $building->building_name = $request->buildingName;
        $building->building_city = $request->buildingCity;
        $building->building_state = $request->buildingState;
        $building->building_zip = $request->buildingZip;
        $building->building_units = $request->buildingUnits;
        $building->building_stories = $request->buildingStories;
        $building->building_build_year = $request->buildingBuildYear;
        $building->building_amenities = $amenities;
        $building->building_description = $request->buildingDescription;
        $building->path_for_building_images_on_s3 = $path_for_images;
        $building->building_images = $images;
        $building->b_listing_type = $request->listingType;
        $building->name_label = $request->name_label;
        $building->amazon_id = $request->amazon_id;

        return $building->save();

    }

    public function deleteBuilding($id)
    {
        $building = $this->getBuilding($id);

        if(!empty($building)){
            $building->delete();
        }

        return $building;

    }

    public function imageDelete($request)
    {
        $building = $this->getBuildingByBuildingId($request->id);

        $images = @json_decode($building->building_images);

        $path = @json_decode($building->path_for_building_images_on_s3);

        if(Storage::disk('s3')->exists($path[$request->key])) {
            Storage::disk('s3')->delete($path[$request->key]);
        }

        unset($images[$request->key]);
        unset($path[$request->key]);

        $path_for_images = \GuzzleHttp\json_encode($path);
        $images_list = \GuzzleHttp\json_encode($images);

        $building->building_images = $images_list;
        $building->path_for_building_images_on_s3 = $path_for_images;

        return $building->save();
    }

    public function labelImageDelete($request)
    {
        $building = $this->getBuildingByBuildingId($request->id);

        $images = @json_decode($building->name_label_image);

        $path = @json_decode($building->path_for_name_label_image);

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

        $building->name_label_image = $images_list;
        $building->path_for_name_label_image = $path_for_images;

        return $building->save();
    }

    public function getAddress($request)
    {
        $searchquery = $request->searchquery;

        if(isset($request->districtID)){
            $district = $request->districtID;

            $buildings = Building::where('building_district_id',$district)
                ->where('building_address','like','%'.$searchquery.'%')
                ->limit(20)->get();
        }else{
            $buildings = Building::where('building_address','like','%'.$searchquery.'%')->get();
        }

        $data = DB::table('filters_data')
            ->where('filter_id', 'filters')
            ->get();

        foreach ($buildings as $building){

            $amenities_array = explode(",", $building->building_amenities);

            $amenities =  $data->whereIn('value', $amenities_array)->pluck('filter_data_id')->all();

            $building->building_amenities = $amenities;

        }

        return response()->json($buildings);
    }

    public function addImages($building, $images, $userID)
    {
        $imagesData = $this->uploadImages($images, $userID);

        $fileNames = $imagesData['fileNames'];
        $filePaths = $imagesData['filePaths'];

        $image = \GuzzleHttp\json_encode($fileNames);
        $path_for_image = \GuzzleHttp\json_encode($filePaths);

        return Building::where('building_id', $building)
            ->update(['building_images' => $image,'path_for_building_images_on_s3'=> $path_for_image,'name_label' => $userID,'amazon_id'=> 1]);

    }

    public function addNameLabelImages($building_id, $images, $userID)
    {
        $building = $this->getBuildingByBuildingId($building_id);

        $imagesData = $this->uploadNameLabelImages($images, $userID);

        $fileNames = $imagesData['fileNames'];
        $filePaths = $imagesData['filePaths'];

        if(!empty($building->name_label_image)){
            $imagesArray = @json_decode($building->name_label_image);
            $images = array_merge($imagesArray, $fileNames);
            $images = \GuzzleHttp\json_encode($images);

            $pathArray = @json_decode($building->path_for_name_label_image);
            $path = array_merge($pathArray, $filePaths);
            $path_for_images = \GuzzleHttp\json_encode($path);
        }else{
            $images = \GuzzleHttp\json_encode($fileNames);
            $path_for_images = \GuzzleHttp\json_encode($filePaths);
        }

        return Building::where('building_id', $building_id)
            ->update(['name_label_image' => $images,'path_for_name_label_image'=> $path_for_images,'name_label' => $userID,'amazon_id'=> 1]);
    }

    public function addDescription($building, $description, $userID)
    {
        return Building::where('building_id', $building)
            ->update(['building_description' => $description,'described' => $userID]);
    }

    public function searchBuildings($search){
        $buildings = Building::where('building_name', 'like', '%' . $search . '%')
                            ->paginate(env('SEARCH_RESULTS_PER_PAGE'))
                            ->appends(request()->query());

        return $buildings;
    }

    public function searchAddresses($search){

        $buildings = Building::where(DB::raw('CONCAT(building_address, " " ,building_city, " ", building_state, " ", building_zip)'), 'like', '%' . $search . '%')
            ->paginate(env('SEARCH_RESULTS_PER_PAGE'))
            ->appends(request()->query());

        return $buildings;
    }


    public function autocomplete($search){

        $buildings = Building::select('id', 'building_name', 'building_city', 'building_state', 'building_zip')
                        ->where('building_name', 'like', '%' . $search . '%')
                        ->orWhere(DB::raw('CONCAT(building_address, " " ,building_city, " ", building_state, " ", building_zip)'), 'like', '%' . $search . '%')
                        ->limit(5)
                        ->get();

        $result = array();
        foreach ($buildings as $building){
            $result[] = array(
                'title' => $building->building_name.', '.$building->building_city.', '.$building->building_state.', '.$building->building_zip,
                'link'  => '/building/' . str_replace(' ','_',$building->building_name) . '/' . str_replace(' ', '_', $building->building_city)
            );
        }

        return $result;
    }

}