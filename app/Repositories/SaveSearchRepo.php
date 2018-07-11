<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\SearchResults;
use App\User;

class SaveSearchRepo
{
    public function getSaveSearch()
    {
        $data =  Auth::user()->getUserResult();

        foreach($data as $item){
            if($item->type_id == 1){
                $saleSaved[] = $item;
            }elseif($item->type_id == 2){
                $rentalSaved[] = $item;
            }
        }

        return compact('saleSaved', 'rentalSaved');
    }

    public function getSavedItems() {
        $data = Auth::user()->getSavedItems(1); //sales
        foreach ($data as $item) {
            $savedSales[] = $item;
        }

        $data = Auth::user()->getSavedItems(2); //rentals
        foreach ($data as $item) {
            $savedRentals[] = $item;
        }

        $data = Auth::user()->getSavedItems(3); //buildings
        //print_r($data); exit;
        foreach ($data as $item) {
            $savedBuildings[] = $item;
        }

        return compact('savedSales', 'savedRentals', 'savedBuildings');
    }

    public function getSaveResult($id)
    {
        return DB::table('user_search_results')->where('user_id',$id)->get();
    }

    public function saveSearch($request){

        $result = SearchResults::find($request->search_id);

        if (!$result){
            return $response = array(
                'status' => 'success',
                'msg' => 'You can not save empty result',
            );
        }

        if(empty($request->saveId) && !empty($request->title) && !empty($request->type)){

            $user = User::find($request->user_id);

            $user->searchResults()->attach($request->search_id,['title' => $request->title, 'type_id' => $request->type,
                'created_at' => new \DateTime(), 'updated_at' => new \DateTime(), 'listing_ids' => $request->ids]);

            if($user){
                return $response = array(
                    'status' => 'success',
                    'msg' => 'Data save successfully',
                );
            }
            else{
                return $response = array(
                    'status' => 'error',
                    'msg' => 'Can not save data, please try again',
                );
            }
        }elseif(empty($request->title) && empty($request->saveId)){
            return $response = array(
                'status' => 'error',
                'msg' => 'input your title',
            );
        }elseif(!empty($request->saveId)){

            $saveSearch =  DB::table('user_search_results')
                ->where('user_id', $request->user_id)
                ->where('id', $request->saveId)
                ->update(['type_id' => $request->type,
                    'search_id' => $request->search_id,
                    'updated_at' => new \DateTime(),
                    'listing_ids' => $request->ids]);

            if($saveSearch){
                return $response = array(
                    'status' => 'success',
                    'msg' => 'Data save successfully',
                );
            }else{
                return $response = array(
                    'status' => 'error',
                    'msg' => 'Can not save try again',
                );
            }
        }

    }

    public function deleteSaveSearch($userID, $searchID)
    {
        return DB::table('user_search_results')->where([
                ['user_id','=',$userID],
                ['search_id','=',$searchID],
            ])->delete();
    }

    public function deleteItem($userID, $sid)
    {
        return DB::table('saved_items')->where([
                ['user_id','=',$userID],
                ['id','=',$sid],
            ])->delete();
    }
}