<?php

namespace App\Http\Controllers;

use App\Repositories\BuildingRepo;
use App\Repositories\FilterRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BuildingController extends Controller
{
    private $building;
    private $filter;

    public function __construct(BuildingRepo $building, FilterRepo $filter)
    {
        $this->building = $building;
        $this->filter = $filter;
    }

    public function show(Request $request){

        $request->user()->authorizeRoles(['admin']);

        $buildings = $this->building->buildingsList();

        return view('buildings', compact('buildings'));
    }

    public function store(Request $request){

        $request->user()->authorizeRoles(['admin']);

        $dataFilters = $this->filter->dataFilters();

        if(!empty($_POST)){
            $validator = Validator::make($request->all(), [
                'listingType' => 'required|numeric',
                'buildingID' => 'required|numeric|unique:buildings,building_id',
                'buildingAddress' => 'required',
                'buildingName' => 'required',
                'buildingCity' => 'required',
                'buildingState' => 'required',
                'buildingUnits' => 'required|numeric',
                'buildingZip' => 'required|numeric',
                'buildingStories' => 'required|numeric',
                'buildingBuildYear' => 'required|numeric',
                'buildingAmenities' => 'required',
                'buildingDescription' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect('/addbuilding')
                    ->withErrors($validator)
                    ->withInput();
            }

            if($this->building->addBuilding($request,$dataFilters)){
                return redirect('/buildings')
                    ->with('status', 'Building save successfully');
            }else{
                return redirect('/addbuilding')
                    ->with('status', 'Can not save, please try again')
                    ->withInput();
            }
        }

        return view('addBuilding', $dataFilters);

    }

    public function edit(Request $request){

        $request->user()->authorizeRoles(['admin']);

        $data = $this->filter->dataFilters();

        $list = $this->building->editBuilding($request->id);

        if(!empty($list)){
            return view('updateBuilding', $data)->with('list', $list);
        }

    }

    public function update(Request $request){

        $request->user()->authorizeRoles(['admin']);

        $validator = Validator::make($request->all(), [
            'listingType' => 'required|numeric',
            'buildingID' => 'required|numeric',
            'buildingAddress' => 'required',
            'buildingName' => 'required',
            'buildingCity' => 'required',
            'buildingState' => 'required',
            'buildingUnits' => 'required|numeric',
            'buildingZip' => 'required|numeric',
            'buildingStories' => 'required|numeric',
            'buildingBuildYear' => 'required|numeric',
            'buildingAmenities' => 'required',
            'buildingDescription' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/editbuilding')
                ->withErrors($validator)
                ->withInput();
        }

        if(!empty($request->id)){

            if($this->building->updateBuilding($request->id, $request, $this->filter->dataFilters())){
                return redirect('/buildings')
                    ->with('status', 'Building update successfully');

            }else{
                return redirect('/updatebuilding')
                    ->with('status', 'Can not save, please try again')
                    ->withInput();
            }

        }else{
            return redirect('/updatebuilding')
                ->with('status', 'Can not save, please try again')
                ->withInput();
        }
    }

    public function delete(Request $request){

        $request->user()->authorizeRoles(['admin']);

        if($this->building->deleteBuilding($request->id)){
            return redirect('/buildings')->with('status', 'Building deleted');
        }

    }

    public function imageDelete(Request $request){

        if($this->building->imageDelete($request)){
            return 'Image deleted';
        }else{
            return 'Failed to delete, please try again';
        }
    }

    public function labelImageDelete(Request $request){

        if($this->building->labelImageDelete($request)){
            return 'Image deleted';
        }else{
            return 'Failed to delete, please try again';
        }
    }

    public function getAddress(Request $request)
    {
        return $this->building->getAddress($request);
    }

}
