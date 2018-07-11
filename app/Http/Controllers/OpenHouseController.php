<?php

namespace App\Http\Controllers;

use App\Repositories\EstateInfoRepo;
use App\Repositories\OpenHouseRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpenHouseController extends Controller
{
    private $openHouse;
    private $estate;

    public function __construct(OpenHouseRepo $openHouse, EstateInfoRepo $estateInfoRepo)
    {
        $this->openHouse = $openHouse;
        $this->estate = $estateInfoRepo;
    }

    public function index(Request $request){

        $request->user()->authorizeRoles(['Owner', 'Agent', 'man']);

        $listings = $this->estate->getUsersActiveListingOpenHouse(Auth::user()->id);

        $openHours = $this->openHouse->getHours();

        return view('openHouse')->with('listings', $listings)->with('openHours', $openHours);
    }

    public function saveOpenHouse(Request $request)
    {
        if(!empty($request->post())) {

            if($this->openHouse->saveOpenHouse($request->listingId, $request->openHouse)){
                return redirect('/openhouse')
                    ->with('status', 'Open House saved successfully!');
            }else{
                return redirect('/openhouse')
                    ->with('status', 'Failed to save Open House, please try again.');
            }
        }
    }

    public function deleteOpenHouse(Request $request)
    {
        if($this->openHouse->deleteOpenHouse($request->id)){
            return "Date deleted successfully.";
        }else{
            return "Failed to delete, please try again.";
        }
    }
}
