<?php

namespace App\Repositories;

use App\OpenHouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class OpenHouseRepo
{
    public function saveOpenHouse($listing_id, $data)
    {
        $openHouse = null;        
        foreach ($data as $item){

            $date = $item['date'];

            if (!$date)
                continue;
                
            $starts = Carbon::createFromFormat('Y-m-d H:i', $date.' '.$item['start']);
            $ends = Carbon::createFromFormat('Y-m-d H:i', $date.' '.$item['end']);

            if(isset($item['appointment'])){
                $appointment = 1;
            }else{
                $appointment = 0;
            }

            if(isset($item['openID']) && !empty($item['openID'])){
                $openHouse = OpenHouse::find($item['openID']);
                $openHouse->listing_id = $listing_id;
                $openHouse->date = $date;
                $openHouse->start_time = $starts;
                $openHouse->end_time = $ends;
                $openHouse->appointment = $appointment;
                $openHouse->save();
            }else{
                $openHouse = new OpenHouse;
                $openHouse->listing_id = $listing_id;
                $openHouse->date = $date;
                $openHouse->start_time = $starts;
                $openHouse->end_time = $ends;
                $openHouse->appointment = $appointment;
                $openHouse->save();
            }
        }
        return $openHouse;
    }

    public function deleteOpenHouse($id)
    {
        $openHouse = OpenHouse::find($id);

        return ($openHouse->delete());

    }

    public function getHours()
    {
        return DB::table('open_hours')->get();
    }


}