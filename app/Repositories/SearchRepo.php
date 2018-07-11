<?php
namespace App\Repositories;

use App\SearchResults;
use App\EstateInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SearchRepo
{
    public function createSearchResults($ids, $requestData, $count)
    {
        return SearchResults::create([
                'ids' => implode(',',$ids),
                'request' => json_encode($requestData),
                'count' => $count
                ]);
    }

    public function getSearchresults($id)
    {
        return SearchResults::find($id);
    }

    public function results($id, $sort, $page)
    {
        $result = $this->getSearchresults($id);

        if (!$result){
            return view('404');
        }

        $ids = $result->ids;

        $count = $result->count;

        $searchData = json_decode($result->request);

        if ($count <= env('SEARCH_RESULTS_PER_PAGE')){
            $next = false;
        }

        $idsArray = array();

        if (strlen($ids)){
            $idsArray = explode(',',$ids);
        }

        $model = EstateInfo::query();

        $model->select('estate_data.*');

        $requestData = $result->request;

        $requestData = json_decode($requestData,true);

        $this->_prepareModel($model,$requestData);

        $feature = clone $model;

        if ($page!=0){
            $model->offset(env('SEARCH_RESULTS_PER_PAGE') * $page);
        }

        $model->orderBy('premium','desc');
        
        if ($sort) {
            if ($sort=='lowest'){
                $model->orderBy('price','asc');
            }

            if ($sort=='highest'){
                $model->orderBy('price','desc');
            }

            if ($sort=='popular'){
                $model->orderBy('views_count','desc');
            }

            if ($sort=='newest'){
                $model->orderBy('estate_data.created_at','desc');
            }

            if ($sort=='oldest'){
                $model->orderBy('estate_data.created_at','asc');
            }
        }

        $results = $model->paginate(env('SEARCH_RESULTS_PER_PAGE'));

        $results = $this->_prepareResults($results);

        //print_r($results[0]); exit;

        $type = isset($requestData['estate_type']) ? $requestData['estate_type'] : 0;

        foreach ($results as $result){
            $resultIds[] = $result->id;
        }

        $listingIds = json_encode($resultIds);

        //echo $feature->toSql(); exit;
        //print_r($feature->getBindings()); exit;
        $features = $feature->where('feature',1)->get();

        if($features->count() > 2){
            $features = $feature->where('feature',1)->get()->random(2);
        }

        $features = $this->_prepareResults($features);

        return compact('results','count','id','sort','next','searchData','type','listingIds','features');
    }

    public function _prepareModel(&$model,$requestData)
    {  
        if (isset($requestData['svalue']) && $requestData['svalue']){
            $model->where(function ($query) use ($requestData) {
                $query->where('full_address', 'like', "%$requestData[svalue]%")
                      ->orWhere(DB::raw("CONCAT(full_address, ' ', `unit`)"), 'like', "%$requestData[svalue]%");
            });
        }

        if (isset($requestData['districts']) && count($requestData['districts'])){

            $model->whereIn('district_id',$requestData['districts']);
        }

        $model->where('active',1);

        if (isset($requestData['types']) && count($requestData['types']))
        {
            $model->whereIn('type_id',$requestData['types']);
        }

        if (isset($requestData['estate_type']) && $requestData['estate_type']){

            $model->where('estate_type',$requestData['estate_type']);

        }

        if (isset($requestData['beds']) && count($requestData['beds'])==2){

            $min = (int)$requestData['beds'][0];

            $max = (int)$requestData['beds'][1];
 
            if ($min > $max){

                $max = (int)$requestData['beds'][0];

                $min = (int)$requestData['beds'][1];

            }

            if ($min > 0){ $model->where('beds', '>=', $min); }

            if ($max > 0){ $model->where('beds', '<=', $max); }

        }

        if (isset($requestData['baths']) && count($requestData['baths'])==2){

            $min = (int)$requestData['baths'][0];

            $max = (int)$requestData['baths'][1];

            if ($min > $max){

                $max = $requestData['baths'][0];

                $min = $requestData['baths'][1];

            }

            if ($min > 0){ $model->where('baths', '>=', $min); }

            if ($max > 0){ $model->where('baths', '<=', $max); }

        }

        if (isset($requestData['price']) && count($requestData['price'])==2){

            $min = floatval($requestData['price'][0]);

            $max = floatval($requestData['price'][1]);

            if ($min > $max){

                $max = floatval($requestData['price'][0]);

                $min = floatval($requestData['price'][1]);
            }

            if ($min){

                $model->where('price', '>=', $min);

            }

            if ($max){

                $model->where('price', '<=', $max);

            }
        }

        if (isset($requestData['filters']) && count($requestData['filters']))
        {
            $model->join('estate_filters','estate_data.id','estate_id');

            foreach ($requestData['filters'] as $filt)
            {
                $model->where('f_'.$filt,1);
            }
        }

        //not used anymore,  updated to  estate_data.logo_path
        //$model->leftjoin('logo_path','logo_path.listing_id','estate_data.listing_id');

        //$model->with('openHouse');
        $model->with(array('openHouse' => function($query) {
             $query->where('end_time', '>', Carbon::now());
        }));

        //print_r($model->toSql());exit;
        return $model;
    }

    public function _prepareResults($results)
    {
        foreach ($results as &$result){

            $images = $result->path_for_images;

            $images = json_decode($images);

            $result->img = "/images/default_image_coming.jpg"; // not found img

            $result->beds = (int)$result->beds;

            $result->baths = (int)$result->baths;

            $result->sq_feet = (int)$result->sq_feet;

            if (count($images)){

                $img =array_shift($images);

                if (!empty($img)){

                    if ($result->estate_type==2 && $result->amazon_id == 0){

                        $result->img =  env('S3_IMG_PATH').'images-rental/'.$img;

                    }elseif($result->estate_type == 2 && $result->amazon_id == 1){

                        $result->img =  env('S3_IMG_PATH_1').$img;

                    }

                    if ($result->estate_type==1 && $result->amazon_id == 0){

                        $result->img =  env('S3_IMG_PATH').'sales-listing-images/'.$img;

                    }elseif($result->estate_type == 1 && $result->amazon_id == 1){

                        $result->img =  env('S3_IMG_PATH_1').$img;

                    }

                }

            }

            $result->beds = (int)$result->beds;

            $result->baths = (int)$result->baths;

            $result->sq_feet = (int)$result->sq_feet;

            $result->name_link = '/show/'.str_replace(' ','_',$result->name).'/'.$result->id;

            $result->price = number_format(floatval($result->price),0, '.',',');

            $result->monthly_cost = number_format($result->monthly_taxes + $result->common_charges + $result->maintenance,0,'.',',');


            if (!empty($result->logo_path)){
                $result->path_to_logo = env('S3_IMG_PATH').'square60/'.$result->logo_path;
            }else {
                $result->path_to_logo = '';
            }
        }

        return $results;

    }
}