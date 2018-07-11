<?php

namespace App\Http\Controllers;

use App\Repositories\SaleSearchRepo;
use Illuminate\Support\Facades\Session;

class SaleSearchController extends Controller
{
    private $rentalSearchRepo;

    public function __construct(SaleSearchRepo $saleSearchRepo)
    {
        $this->saleSearchRepo = $saleSearchRepo;
    }

    public function index()
    {
        $data = $this->saleSearchRepo->dataFilters();

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

        $data['stype'] = 'sales';
        return view('salesearch', $data);
    }

}
