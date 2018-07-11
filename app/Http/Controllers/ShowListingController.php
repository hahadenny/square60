<?php

namespace App\Http\Controllers;

use App\Repositories\EstateInfoRepo;
use Illuminate\Http\Request;

class ShowListingController extends Controller
{
    private $estate;

    /**
     * ShowListingController constructor.
     * @param $estate
     */
    public function __construct(EstateInfoRepo $estate)
    {
        $this->estate = $estate;
    }

    public function showSell(Request $request){

        $request->user()->authorizeRoles(['admin']);

        $data_listing = $this->estate->getListings(1);

        return view('showSell', compact('data_listing'));
    }

    public function showRental(Request $request){

        $request->user()->authorizeRoles(['admin']);

        $dataListing = $this->estate->getListings(2);

        return view('showRental', compact('dataListing'));
    }
}
