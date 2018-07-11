<?php

namespace App\Http\Controllers;

use App\EstateInfo;
use Illuminate\Support\Facades\DB;
use App\User;
use App\SearchResults;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{
    public function index()
    {
       $saveSearch = $this->getUserSaveSearch();

       foreach ($saveSearch as $item){

           $userEmail = $this->userEmail($item->user_id);

           $saveDate = $item->updated_at;

           $saveListingIds = json_decode($item->listing_ids);

           $updateListings = $this->getUpdateListings($saveListingIds,$saveDate);

           $newListings = $this->getNewListings($item->search_id,$saveListingIds);

           $results = $this->_prepareResults($updateListings);

           $newResults = $this->_prepareResults($newListings);

           if(!$results->isEmpty()){
               Mail::to($userEmail)->send(new \App\Mail\ResultMail($results));
           }

           if(!empty($newResults)){
               Mail::to($userEmail)->send(new \App\Mail\ResultMail($newResults));
           }
       }
    }

    protected function userEmail($user_id){
        return User::where('id', $user_id)->pluck('email');
    }

    protected function getUserSaveSearch()
    {
        return DB::table('user_search_results')->get();
    }

    protected function getUpdateListings($listingsIds,$updateDate)
    {
        return EstateInfo::whereIn('id', $listingsIds)
            ->where('updated_at','>',$updateDate)
            ->get();
    }

    protected function getNewListings($searchId,$listingsIds)
    {
        $result = SearchResults::find($searchId);

        $model = EstateInfo::query();

        $requestData = $result->request;

        $requestData = json_decode($requestData,true);

        $this->_prepareModel($model,$requestData);

        $results = $model->get();

        $newResults = array();
        foreach ($results as $value)
        {
           if (!in_array($value->id, $listingsIds)){
              $newResults[] = $value;
           }
        }

       return $newResults;
    }

    protected function _prepareResults($results){
        foreach ($results as &$result){
            $images = $result->path_for_images;
            $images = json_decode($images);
            $result->img = "https://s3.us-east-2.amazonaws.com/new-developer/unit_images/137823/10236523/299542512.jpg"; // not found img
            $result->beds = (int)$result->beds;
            $result->baths = (int)$result->baths;
            $result->sq_feet = (int)$result->sq_feet;
            if (count($images)){
                $img =array_shift($images);
                if (!empty($img)){
                    if ($result->estate_type==2 && $result->amazon_id == 0){
                        $result->img =  env('S3_IMG_PATH').'rental-listing-images/'.$img;
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
        }
        return $results;
    }

    protected function _prepareModel(&$model,$requestData){
        if (isset($requestData['districts']) && count($requestData['districts'])){
            $model->whereIn('district_id',$requestData['districts']);
        }
        if (isset($requestData['types']) && count($requestData['types'])){
            $model->whereIn('type_id',$requestData['types']);
        }
        if (isset($requestData['estate_type']) && count($requestData['estate_type'])){
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
            if ($max < 8){ $model->where('beds', '<=', $max); }
        }
        if (isset($requestData['baths']) && count($requestData['baths'])==2){
            $min = (int)$requestData['baths'][0];
            $max = (int)$requestData['baths'][1];
            if ($min > $max){
                $max = $requestData['baths'][0];
                $min = $requestData['baths'][1];
            }
            if ($min > 0){ $model->where('baths', '>=', $min); }
            if ($max < 8){ $model->where('baths', '<=', $max); }
        }
        if (isset($requestData['price']) && count($requestData['price'])==2){
            $min = floatval($requestData['price'][0]);
            $max = floatval($requestData['price'][1]);
            if ($min> $max){
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
        if (isset($requestData['filters']) && count($requestData['filters'])){
            $model->join('estate_filters','id','estate_id');
            foreach ($requestData['filters'] as $filt) {
                $model->where('f_'.$filt,1);
            }
        }
    }


}
