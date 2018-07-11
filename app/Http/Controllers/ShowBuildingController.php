<?php

namespace App\Http\Controllers;

use App\Repositories\ShowRepo;
use App\Repositories\BuildingRepo;
use Illuminate\Http\Request;

class ShowBuildingController extends Controller
{
    private $show;
    private $building;

    public function __construct(ShowRepo $show, BuildingRepo $buildingRepo)
    {
        $this->show = $show;
        $this->building = $buildingRepo;
    }

    public function index(Request $request, $name, $city){

        $id = $request->segment(3);

        $result = $this->building->getBuilding($name, $city);
        //print_r($result); exit;
        //print_r($result->filterType->value); exit;

        if (!$result)
        {
            return view('404');
        }

        return view('building2')->with($this->show->showBuilding($result, $name, $city));
    }

    public function save(Request $request) {
        //print_r($request->name); exit;
        $result = $this->show->saveBuilding($request);
        
        if (!$result){
            return redirect('/building/'.$request->name.'/'.$request->city)->with('status', 'Building already saved.');
        }
        else
            return redirect('/building/'.$request->name.'/'.$request->city)->with('status', 'Building saved successfully!');
    }
}
