<?php

namespace App\Http\Controllers;

use App\Repositories\RentalSearchRepo;
use Illuminate\Support\Facades\Session;

class RentalSearchController extends Controller
{
    private $rentalSearchRepo;

    public function __construct(RentalSearchRepo $rentalSearchRepo)
    {
        $this->rentalSearchRepo = $rentalSearchRepo;
    }

    public function index()
    {
        $data = $this->rentalSearchRepo->dataFilters();

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

        $data['stype'] = 'rentals';
        return view('rentalsearch', $data);
    }

}
