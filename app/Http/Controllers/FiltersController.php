<?php

namespace App\Http\Controllers;

use App\Repositories\FilterRepo;
use Illuminate\Support\Facades\Session;

class FiltersController extends Controller
{
    private $filtersRepo;

    public function __construct(FilterRepo $filtersRepo)
    {
        $this->filtersRepo = $filtersRepo;
    }

    public function index()
    {
        $data = $this->filtersRepo->dataFilters();

        $previousUrl = url()->previous();

        if( strpos($previousUrl, 'saved-searches') == true){
            $url = 1;
        }else{
            $url = 0;
        }

        if($url==0)
        {
            //session::forget('searchData');
        }

        //return view('index', $data);
        return view('index2', $data);
    }

}
