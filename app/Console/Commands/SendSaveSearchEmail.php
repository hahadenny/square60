<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\EstateInfo;
use App\User;
use App\SearchResults;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class SendSaveSearchEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:search';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $saveSearch = $this->getUserSaveSearch();

        echo "Saved Search:\n";

        foreach ($saveSearch as $item){

            $userEmail = $this->userEmail($item->user_id);
            //$userEmail = 'denny0709@hotmail.com';  //for testing

            $updatedAt = $item->updated_at;            

            //No use, becoz only 20 max. records saved in DB 
            //$saveListingIds = json_decode($item->listing_ids);
            //$updateListings = $this->getUpdateListings($saveListingIds,$updatedAt);
            //$results = $this->_prepareResults($updateListings);

            $newListings = $this->getNewListings($item->search_id, $updatedAt);            

            $newResults = $this->_prepareResults($newListings);
            
            echo $userEmail.'='.count($newResults)."\n";

            //$domain = URL::route('main');
            $domain = env('APP_URL');

            $subject = "New/Updated Listings (".$item->title.")";

            if(!empty($newResults) && count($newResults)){                 
                //if ($userEmail == '["denny0709@gmail.com"]') {//for testing
                Mail::to($userEmail)->send(new \App\Mail\ResultMail($newResults, $domain, $subject, $updatedAt, $item->search_id));
                echo "new/updated email sent to $userEmail......\n";
                sleep(5);
            }

            //put it at last to make sure email is sent successfully first
            //echo $item->search_id; exit;
            $this->updateSaveSearchUpdateAt($item->search_id);
        }

        //send saved items (listings only) as well
        $savedItems = $this->getUserSavedItems();

        $sItems = array();
        foreach ($savedItems as $item){
            $sItems[$item->user_id][] = $item;
        }

        echo "Saved Items:\n";

        foreach ($sItems as $user_id => $items){
            $userEmail = $this->userEmail($user_id);
            //$userEmail = 'denny0709@hotmail.com';  //for testing
            $saveListingIds = array();
            $updatedAt = '';
            foreach ($items as $item) {
                $saveListingIds[] = $item->save_id;
                $updatedAt = $item->updated_at;
            }         

            $updateListings = $this->getUpdateListings($saveListingIds,$updatedAt);
            //print_r($updateListings); exit;
            $results = $this->_prepareResults($updateListings);
            //print_r($results); exit;
            
            echo $userEmail.'='.count($results)."\n";

            //$domain = URL::route('main');
            $domain = env('APP_URL');

            $subject = "Updated Saved Listings";

            if(!empty($results) && count($results)){                 
                //if ($userEmail == '["denny0709@gmail.com"]') {//for testing
                Mail::to($userEmail)->send(new \App\Mail\ResultMail($results, $domain, $subject, $updatedAt, ''));
                echo "updated saved items email sent to $userEmail......\n";
                sleep(5);
            }

            //put it at last to make sure email is sent successfully first
            //echo $item->search_id; exit;
            $this->updateSavedItemsUpdateAt($user_id);
        }
    }

    protected function userEmail($user_id){
        return User::where('id', $user_id)->pluck('email');
    }

    protected function getUserSaveSearch()
    {
        return DB::table('user_search_results')->get();
    }

    protected function getUserSavedItems()
    {
        return DB::table('saved_items')->whereIn('type', array('1', '2'))->get(); //sales and rentals listings only, not buildings
    }

    protected function getUpdateListings($listingsIds,$updateDate)
    {
        return EstateInfo::whereIn('id', $listingsIds)
            ->where('updated_at','>',$updateDate)
            ->where('active', '=', 1)
            ->get();
    }

    protected function getNewListings($searchId,$updatedAt)
    {
        $result = SearchResults::find($searchId);

        $model = EstateInfo::query();

        $requestData = $result->request;

        $requestData = json_decode($requestData,true);

        $this->_prepareModel($model,$requestData,$updatedAt);

        $results = $model->get();

        //echo count($results); exit;

        /*$newResults = array();
        foreach ($results as $value)
        {
            if (!in_array($value->id, $listingsIds)){
                $newResults[] = $value;
            }
        }*/

        //echo count($newResults); exit;

        return $results;
    }

    protected function _prepareResults($results){
        foreach ($results as &$result){
            $images = $result->path_for_images;
            $images = json_decode($images);
            $result->img = env('APP_URL')."/images/default_image_coming.jpg"; // not found img
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
            $result->last_price = number_format(floatval($result->last_price),0, '.',',');
            $result->monthly_cost = number_format($result->monthly_taxes + $result->common_charges + $result->maintenance,0,'.',',');
        }
        return $results;
    }

    protected function _prepareModel(&$model,$requestData,$updatedAt){

        //only get the ones after the date of saved search or last new/update listing email
        $model->where('estate_data.updated_at', '>', $updatedAt)->where('estate_data.active', '=', 1);

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

        $model->orderBy('estate_data.updated_at', 'desc');
    }

    protected function updateSaveSearchUpdateAt($search_id) {
        return DB::table('user_search_results')
                   ->where('search_id', $search_id)
                   //->update(['updated_at' => DB::raw('now()')]);
                   ->update(['updated_at' => Carbon::now()]);
    }

    protected function updateSavedItemsUpdateAt($user_id) {
        return DB::table('saved_items')
                   ->where('user_id', $user_id)
                   //->update(['updated_at' => DB::raw('now()')]);
                   ->update(['updated_at' => Carbon::now()]);
    }
}
