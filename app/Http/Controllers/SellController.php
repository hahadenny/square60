<?php

namespace App\Http\Controllers;

use App\Repositories\OpenHouseRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BuildingRepo;
use App\Repositories\FilterRepo;
use App\Repositories\EstateInfoRepo;


class SellController extends Controller
{
    private $building;
    private $filter;
    private $estate;
    private $open;

    /**
     * SellController constructor.
     * @param $building
     * @param $filter
     */
    public function __construct(BuildingRepo $building, FilterRepo $filter, EstateInfoRepo $estateRepo, OpenHouseRepo $openRepo)
    {
        $this->building = $building;
        $this->filter = $filter;
        $this->estate = $estateRepo;
        $this->open = $openRepo;
    }


    public function index(Request $request){

        $request->user()->authorizeRoles(['Owner', 'Agent', 'Admin', 'man']);

        $data = $this->filter->dataFilters();

        $openHours = $this->open->getHours();

        return view('sell', $data)->with('openHours', $openHours);
    }

    public function store(Request $request){

        $request->user()->authorizeRoles(['Owner', 'Agent', 'Admin', 'man']);

        if($request->has('street_address')){

            if(isset($request->model) && $request->model == 1  && isset($request->id)){
                $estate = $this->estate->findListing($request->id);
            }elseif(isset($request->model) && $request->model == 0){
                $estate = $this->estate->newListing();
            }
            
            $rules = [
                'street_address' => 'required|min:3',
                'zip' => 'required',
                //'year_built' => $request->year_built ? 'numeric' : '',
                'type' => 'required',
                'district' => 'required',
                'price' => 'required|numeric',
                'bed' => in_array($request->type, array('6', '7')) ? 'required|numeric' : '',
                'bath' => in_array($request->type, array('6', '7')) ? 'required|numeric' : '',
                'size' => in_array($request->type, array('6', '8', '9')) ? 'required|numeric' : '',
                'unit' => in_array($request->type, array('8', '9')) ? 'required|numeric' : '',
                'charge' => $request->type == 6 ? 'required|numeric' : '',
                'tax' => in_array($request->type, array('6', '8', '9')) ? 'required|numeric' : '',
                'maintenance' => $request->type == 7 ? 'required|numeric' : '',
                'description' => 'required',
                //'feature' => 'required',
                //'filters' => 'required',
                //'broker' => 'required',
                //'commission' => 'required|numeric',
                'room' => in_array($request->type, array('6', '7')) ? 'required|numeric' : '',
                'apartment' => in_array($request->type, array('6', '7')) ? 'required' : '',
                'boro' => 'required',
                'city' => 'required',
                'ny' => 'required',
                //'image' => empty($estate->images) ? 'required | mimes:jpeg,jpg,png | max:1000' : '',
                //'image.0' => 'required|image',
                'image' => 'array|min:1|max:20',
                'plans' => 'array|min:0|max:20',
                'condition' => 'required'
            ];            

            $images = $request->image;
            $plans = $request->plans;

            if (!empty($images)) {
                foreach ($images as $key => $image) { // add individual rules to each image                    
                    $rules[sprintf('image.%d', $key)] = 'required|image|max:20480';
                }
            }

            if (!empty($plans)) {
                foreach ($plans as $key => $plan) { // add individual rules to each image                    
                    $rules[sprintf('plans.%d', $key)] = 'required|image|max:20480';
                }
            }

            if (Auth::user()->isAgent() || Auth::user()->isMan()) {
                $rules['agreement'] = 'array|min:1|max:20';

                $agreements = $request->agreement;

                if (!empty($agreements)) {
                    foreach ($agreements as $key => $agreement) { // add individual rules to each image                    
                        $rules[sprintf('agreement.%d', $key)] = 'required|mimes:jpeg,bmp,png,gif,svg,pdf|max:20480';
                    }
                }
             }

            if (Auth::user()->isOwner()) {
                $rules['deed'] = 'array|min:1|max:20';
                $rules['utilitybill'] = 'array|min:1|max:20';
                $rules['photoid'] = 'array|min:1|max:20';

                $deeds = $request->deed;
                $utilitybills = $request->utilitybill;
                $photoids = $request->photoid;

                if (!empty($deeds)) {
                    foreach ($deeds as $key => $deed) { // add individual rules to each image                    
                        $rules[sprintf('deed.%d', $key)] = 'required|mimes:jpeg,bmp,png,gif,svg,pdf|max:20480';
                    }
                }

                if (!empty($utilitybills)) {
                    foreach ($utilitybills as $key => $utilitybill) { // add individual rules to each image                    
                        $rules[sprintf('utilitybill.%d', $key)] = 'required|mimes:jpeg,bmp,png,gif,svg,pdf|max:20480';
                    }
                }

                if (!empty($photoids)) {
                    foreach ($photoids as $key => $photoid) { // add individual rules to each image                    
                        $rules[sprintf('photoid.%d', $key)] = 'required|mimes:jpeg,bmp,png,gif,svg,pdf|max:20480';
                    }
                }
            }

            $validator = Validator::make($request->all(), $rules);

            $first_err = $validator->errors()->first();

            $img_err = '';
            if (preg_match('/The image\./', $first_err)) {
                $img_err = preg_replace('/\.(\d+) /', ' ', $first_err);
            }

            $plan_err = '';
            if (preg_match('/The plans\./', $first_err)) {
                $plan_err = preg_replace('/\.(\d+) /', ' ', $first_err);
            }

            $agreement_err = '';
            if (preg_match('/The agreement\./', $first_err)) {
                $agreementn_err = preg_replace('/\.(\d+) /', ' ', $first_err);
            }

            $deed_err = '';
            if (preg_match('/The deed\./', $first_err)) {
                $deed_err = preg_replace('/\.(\d+) /', ' ', $first_err);
            }

            $utilitybill_err = '';
            if (preg_match('/The utilitybill\./', $first_err)) {
                $utilitybill_err = preg_replace('/\.(\d+) /', ' ', $first_err);
            }

            $photoid_err = '';
            if (preg_match('/The photoid\./', $first_err)) {
                $photoid_err = preg_replace('/\.(\d+) /', ' ', $first_err);
            }

            //print_r($validator->errors()); exit;
            //echo $img_err; exit;

            if ($validator->fails()) {
                return redirect('/home/listing/sell')
                    ->withErrors($validator)
                    ->withInput()
                    ->with('status', 'Failed to submit listing.')
                    ->with('img_err', $img_err)
                    ->with('plan_err', $plan_err)
                    ->with('agreement_err', $agreement_err)
                    ->with('deed_err', $deed_err)
                    ->with('utilitybill_err', $utilitybill_err)
                    ->with('photoid_err', $photoid_err);
            }

            if (!$request->filters)
                $request->filters = array();

            $addSell = $this->estate->addSellListing($request, $estate);

            if($addSell){

                if (Auth::user()->isAdmin()){
                    return redirect('/allsell')
                        ->with('status', 'List saved successfully!');
                }else{
                    if($request->feature == 1 ){
                        return redirect('feature?id='.$estate->id);
                    }else{
                    return redirect('/home/listing')
                        ->with('status', 'Your sale listing is saved successfully!');
                    }
                }

            }else{
                return redirect('/home/listing/sell')
                    ->with('status', 'Failed to save, please try again.')
                    ->withInput();
            }
        }
        elseif (isset($request->id) && isset($request->openhouseonly) && isset($request->openHouse)) {
            //print_r($request->all()); exit;
            $openHouse = $this->open->saveOpenHouse($request->id, $request->openHouse);
            //print_r($openHouse); exit;

            if($openHouse){

                if (Auth::user()->isAdmin()){
                    return redirect('/allsell')
                        ->with('status', 'List saved successfully!');
                }else{
                    if($request->feature == 1 ){
                        return redirect('feature?id='.$estate->id);
                    }else{
                        return redirect('/home/listing')
                            ->with('status', 'Your sale listing is saved successfully!');
                    }
                }

            }else{
                return redirect('/home/listing')
                    ->with('status', 'Failed to save, please try again.')
                    ->withInput();
            }
        }
        else {
            return redirect('/home/listing');
        }
    }



    public function submit(Request $request)
    {
        $request->user()->authorizeRoles(['Owner', 'Agent', 'Admin', 'man']);

        $submit = $this->estate->submitListing($request);

        if (Auth::user()->isAdmin()){
            return redirect('/allsell')->with('status', $submit);
        }else{
            return redirect('/home/listing')->with('status', $submit);
        }
    }

    public function edit(Request $request)
    {
        $request->user()->authorizeRoles(['Owner', 'Agent', 'Admin', 'man']);

        if(!empty($request->id) && !empty($request->submit)){

            if(strtolower($request->submit) === 'edit'){

                $list = $this->estate->editListing($request);

                $data = $this->filter->dataFilters();

                $openHours = $this->open->getHours();

                return view('sell', $data)->with('list', $list)->with('openHours', $openHours);
            }
        }
    }

    public function delete(Request $request)
    {
        $request->user()->authorizeRoles(['Owner', 'Agent', 'Admin', 'man']);

        if(strtolower($request->submit) == 'delete' && isset($request->id)){

            $list = $this->estate->deleteListing($request->id);

            if($list){
                if (Auth::user()->isAdmin()){
                    return redirect('/allsell')->with('status', 'List deleted.');
                }else{
                    return redirect('/home/listing')->with('status', 'List deleted.');
                }
            }else{
                if (Auth::user()->isAdmin()){
                    return redirect('/allsell')->with('status', 'Failed to delete listing, please try again.');
                }else{
                    return redirect('/home/listing')->with('status', 'Failed to delete listing, please try again.');
                }
            }
        }
    }

    public function deleteImage(Request $request)
    {
        $request->user()->authorizeRoles(['Owner', 'Agent', 'Admin', 'man']);

        if($this->estate->deleteImages($request)){
            return 'Image deleted.';
        }else{
            return 'Failed to delete image, please try again.';
        }
    }
}
