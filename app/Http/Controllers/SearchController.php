<?php
namespace App\Http\Controllers;
use App\AgentInfo;
use App\AgentsBuildings;
use App\AgentsListings;
use App\Building;
use App\EstateInfo;
use App\EstateFilters;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Repositories\AgentRepo;
use App\Repositories\SearchRepo;
use App\Repositories\FilterRepo;
use App\Repositories\BuildingRepo;
use App\Repositories\EstateInfoRepo;

class SearchController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $searchRepo;
    private $filterRepo;
    private $estate;
    private $agent;
    private $building;

    public function __construct(SearchRepo $searchRepo, FilterRepo $filterRepo, EstateInfoRepo $estateRepo, AgentRepo $agentRepo, BuildingRepo $buildingRepo)
    {
        $this->searchRepo = $searchRepo;
        $this->filterRepo = $filterRepo;
        $this->estate = $estateRepo;
        $this->agent = $agentRepo;
        $this->building = $buildingRepo;
    }

    public function search(Request $request){

        $model = $this->estate->queryEstate();

        $requestData = $request->post();

        $this->searchRepo->_prepareModel($model,$requestData);

        $count = $model->count();
        //echo $count; exit;

        $next = false;

	    session(['searchData' => json_encode($requestData)]);

        if (!$count){

            $results = $model->paginate(env('SEARCH_RESULTS_PER_PAGE'));

            $sort = false;

            $id = 0;

            $filtersData = $this->filterRepo->dataFilters();

            $type = 0;

            $searchData = (object)$requestData;

            $listingIds = '';

            return view('results2',array_merge($filtersData,compact('results','count','sort','id','type','next','districts','searchData','listingIds')));

        }

        $results = $model->limit(env('SEARCH_RESULTS_PER_PAGE'))->get();

        $ids = array();

        if ($count <= env('SEARCH_RESULTS_PER_PAGE')){

            foreach ($results as $result){

                $ids[] = $result->id;

            }

        }

        $searchId = $this->searchRepo->createSearchResults($ids,$requestData,$count);

        if (isset($requestData['sort']) && $requestData['sort'])
            return redirect('search/'.$searchId->id.'/'.$requestData['sort']);
        else
            return redirect('search/'.$searchId->id);
    }


    public function searchAgents(Request $request){

        $search = $request->get('search');

        $agents = $this->agent->searchAgents($search);

        return view('search', compact('agents', 'search'));
    }


    public function searchAddresses(Request $request){

        $search = $request->get('search');

        $buildings = $this->building->searchAddresses($search);

        return view('search', compact('buildings', 'search'));
    }


    public function searchBuildings(Request $request){

        $search = $request->get('search');

        $buildings = $this->building->searchBuildings($search);

        return view('search', compact('buildings', 'search'));
    }


    public function results($id = 0, $sort = 'newest',$page = 0){

        if ($id == 0)
            return view('404');

        $result = $this->searchRepo->results($id, $sort, $page);
        //print_r($result['features']); exit;

        $data = $this->filterRepo->dataFilters();

        if (isset($result['searchData']))
	        session(['searchData' => json_encode($result['searchData'])]);

        return view('results2')->with($result)->with($data);
    }

    public function saveSearch($id = 0, $sort = 'newest',$page = 0){

        $result = $this->searchRepo->results($id, $sort, $page);

        $data = $this->filterRepo->dataFilters();

        session(['searchData' => json_encode($result['searchData'])]);

        if (Auth::guest()){
            return view('404');
        }else{
            //return view('saveSearch')->with($result)->with($data);
            return view('saveSearch2')->with($result)->with($data);
        }

    }


    public function autocompleteAgent(Request $request){

        $search = $request->get('search');

        $agents = $this->agent->autocomplete($search);

        return response()->json($agents);
    }


    public function autocompleteBuilding(Request $request){

        $search = $request->get('search');

        $buildings = $this->building->autocomplete($search);

        //merge with agents
        $agents = $this->agent->autocomplete($search);

        foreach ($agents as $agent)
            array_push($buildings, $agent);

        return response()->json($buildings);
    }


    //temp method delete when database ready

    public function parse_old( Request $request){

        //$fileDir = '/var/www/estate-dev.gabsoft.net/estates_raw3csv.csv';

        $path = $request->get('path');

        $path1 = 'F:\OpenServer\domains\realestate.local\sales_info_1.csv';

        $data = @file_get_contents($path1);

        if (!$data){

            echo('wrong path '.$path);

            exit;

        }

        set_time_limit(0);

        ignore_user_abort(true);

//        DB::table('estate_data')->truncate();

//        DB::table('estate_filters')->truncate();

        $structureData = array();

        if (!$data){
            echo('not load');
            exit;
        }

        if ($data){

            $count = 0;

            $estates = explode("DELIMITERDELIMETER",$data);

            $dataStruct = array();

            $struct1 = array();

            foreach ($estates as $key=>$estateLine) {

                if ($key == 0) {

                    $dataStruct = explode(';', $estateLine);

                    foreach ($dataStruct as $columnName) {

                        $struct1[trim($columnName)] = '';

                    }

                    continue;

                }

                $estateInfo = explode(';',$estateLine);

                $ii=0;

                foreach ($struct1 as $keyName=>$v){
                    if (!isset($estateInfo[$ii])){
                        continue;
                    }

                    $struct1[$keyName] = trim($estateInfo[$ii]);
                    $ii++;
                }

                $structureData[$struct1['Listing ID']]= $struct1;

            }

        }

        $structureDataImages = array();

        $path1 = 'F:\OpenServer\domains\realestate.local\sales_path_1.csv';

        $data = @file_get_contents($path1);

        if (!$data){
            echo('wrong path images'.$path1);
            exit;
        }

        set_time_limit(0);
        ignore_user_abort(true);

//        DB::table('estate_data')->truncate();

//        DB::table('estate_filters')->truncate();

        if (!$data){
            echo('not load');
            exit;
        }

        if ($data){

            $count = 0;

            $estates = explode("DELIMITERDELIMETER",$data);

            $dataStruct = array();

            $struct1 = array();

            foreach ($estates as $key=>$estateLine) {

                if ($key == 0) {

                    $dataStruct = explode(':', $estateLine);

                    foreach ($dataStruct as $columnName) {

                        $struct1[trim($columnName)] = '';

                    }

                    continue;

                }

                $estateInfo = explode(':',$estateLine);

                $ii=0;

                foreach ($struct1 as $keyName=>$v){

                    if (!isset($estateInfo[$ii])){

                        continue;

                    }

                    $struct1[$keyName] = trim($estateInfo[$ii]);

                    $ii++;

                }

                $structureDataImages[$struct1['Listing ID']]= $struct1;

            }

        }

        //TODO here

        if ($structureData && $structureDataImages) {

            $count =    $this->parseEstateDataSales($structureData,$structureDataImages);

            echo('Added '.$count);

        }else {

            echo    'Nothing to parse';

        }

        exit;



    }

    public function parse( Request $request){

        //$fileDir = '/var/www/estate-dev.gabsoft.net/estates_raw3csv.csv';

        $path = $request->get('path');

        $path1 = 'F:\OpenServer\domains\realestate.local\sales_csv.csv';

        // $path1 = 'F:\OpenServer\domains\realestate.local\rentals_csv.csv';

        $data = @file_get_contents($path1);

        if (!$data){

            echo('wrong path '.$path1);

            exit;

        }

        set_time_limit(0);

        ignore_user_abort(true);

//        DB::table('estate_data')->truncate();

//        DB::table('estate_filters')->truncate();

        $structureData = array();

        if (!$data){

            echo('not load');

            exit;

        }

        if ($data){

            $count = 0;

            $estates = explode("DELIMITERDELIMETER",$data);

            $dataStruct = array();

            $struct1 = array();

            foreach ($estates as $key=>$estateLine) {

                if ($key == 0) {

                    $dataStruct = explode('|', $estateLine);

                    foreach ($dataStruct as $columnName) {

                        $struct1[trim($columnName)] = '';

                    }

                    continue;

                }

                $estateInfo = explode('|',$estateLine);

                $ii=0;

                foreach ($struct1 as $keyName=>$v){

                    if (!isset($estateInfo[$ii])){

                        continue;

                    }

                    $struct1[$keyName] = trim($estateInfo[$ii]);

                    $ii++;

                }

                $structureData[$struct1['Listing ID']]= $struct1;

            }

        }

        $structureDataImages = array();

        $path1 = 'F:\OpenServer\domains\realestate.local\sales_path_1.csv';

        // $path1 = 'F:\OpenServer\domains\realestate.local\rentals_imgs_csv.csv';

        $data = @file_get_contents($path1);

        if (!$data){

            echo('wrong path images'.$path1);

            exit;

        }

        set_time_limit(0);

        ignore_user_abort(true);

//        DB::table('estate_data')->truncate();

//        DB::table('estate_filters')->truncate();

        if (!$data){

            echo('not load');

            exit;

        }

        if ($data){

            $count = 0;

            $estates = explode("DELIMITERDELIMETER",$data);

            $dataStruct = array();

            $struct1 = array();

            foreach ($estates as $key=>$estateLine) {

                if ($key == 0) {

                    $dataStruct = explode('|', $estateLine);

                    foreach ($dataStruct as $columnName) {

                        $struct1[trim($columnName)] = '';

                    }

                    continue;

                }

                $estateInfo = explode('|',$estateLine);

                $ii=0;

                foreach ($struct1 as $keyName=>$v){

                    if (!isset($estateInfo[$ii])){

                        continue;

                    }

                    $struct1[$keyName] = trim($estateInfo[$ii]);

                    $ii++;

                }

                $structureDataImages[$struct1['Listing ID']]= $struct1;

            }

        }

        //TODO here

        if ($structureData && $structureDataImages) {

            $count =    $this->parseEstateDataSales($structureData,$structureDataImages);

            echo('Added '.$count);

        }else {

            echo    'Nothing to parse';

        }

        exit;



    }



    public function parseRental( Request $request){

        //$fileDir = '/var/www/estate-dev.gabsoft.net/estates_raw3csv.csv';

        $path = $request->get('path');

        $path1 = 'C:\OSPanel\domains\hypro.loc\rental.csv';

        $data = @file_get_contents($path1);

        if (!$data){

            echo('wrong path '.$path1);

            exit;

        }

        set_time_limit(0);

        ignore_user_abort(true);

//        DB::table('estate_data')->truncate();

//        DB::table('estate_filters')->truncate();

        $structureData = array();

        if (!$data){

            echo('not load');

            exit;

        }

        if ($data){

            $count = 0;

            $estates = explode("DELIMITERDELIMETER",$data);

            $dataStruct = array();

            $struct1 = array();

            foreach ($estates as $key=>$estateLine) {

                if ($key == 0) {

                    $dataStruct = explode('|', $estateLine);

                    foreach ($dataStruct as $columnName) {

                        $struct1[trim($columnName)] = '';

                    }

                    continue;

                }

                $estateInfo = explode('|',$estateLine);

                $ii=0;

                foreach ($struct1 as $keyName=>$v){

                    if (!isset($estateInfo[$ii])){

                        continue;

                    }

                    $struct1[$keyName] = trim($estateInfo[$ii]);

                    $ii++;

                }

                $structureData[$struct1['Listing ID']]= $struct1;

            }

        }

        /*$structureDataImages = array();

        $path1 = 'F:\OpenServer\domains\realestate.local\rentals_imgs_csv.csv';

        $data = @file_get_contents($path1);

        if (!$data){

            echo('wrong path images'.$path1);

            exit;

        }

        set_time_limit(0);

        ignore_user_abort(true);

//        DB::table('estate_data')->truncate();

//        DB::table('estate_filters')->truncate();

        if (!$data){

            echo('not load');

            exit;

        }

        if ($data){

            $count = 0;

            $estates = explode("DELIMITERDELIMETER",$data);

            $dataStruct = array();

            $struct1 = array();

            foreach ($estates as $key=>$estateLine) {

                if ($key == 0) {

                    $dataStruct = explode('|', $estateLine);

                    foreach ($dataStruct as $columnName) {

                        $struct1[trim($columnName)] = '';

                    }

                    continue;

                }

                $estateInfo = explode('|',$estateLine);

                $ii=0;

                foreach ($struct1 as $keyName=>$v){

                    if (!isset($estateInfo[$ii])){

                        continue;

                    }

                    $struct1[$keyName] = trim($estateInfo[$ii]);

                    $ii++;

                }

                $structureDataImages[$struct1['Listing ID']]= $struct1;

            }

        }*/

        //TODO here

        if ($structureData ) {

            $count =    $this->parseEstateDataRentals($structureData);

            echo('Added '.$count);

        }else {

            echo    'Nothing to parse';

        }

        exit;

    }

    protected function getBuildingInfo($buildingAddress){

        return Building::where('building_address',$buildingAddress)->first();

    }

    protected function getAgentInfo(){

        return AgentInfo::get()->random(1);

    }

    protected function getRandomImages(){

        return EstateInfo::where('estate_type', 2)->get()->random(1);

    }

    protected  function getBuilding($id){
        return Building::find($id);
    }

    public function parseEstateDataRentals($structureData){

        $count = 0;

        $filters_data = DB::table('filters')

            ->leftjoin('filters_data', 'filters.name', '=', 'filters_data.filter_id')

            ->get();

        $types = array();

        $subDistricts = array();

        $amneties = array();

        foreach ($filters_data as $fil){

            if ($fil->name=='sub_districts'){

                $subDistricts[] = $fil;

            }

            if ($fil->name == 'type'){

                $types[] = $fil;

            }

            if ($fil->name == 'filters'){

                $amneties[] = $fil;

            }

        }

        //start

        foreach ($structureData as $estateInfo){

            $isset = EstateInfo::where('listing_id',$estateInfo['Listing ID'])->first();

            if ($isset){

                continue;

            }

            $buildingType = $estateInfo['Building Type'];

            $typeId = $this->getTypeIdByType($buildingType);

            /*if (isset($structureDataImages[$estateInfo['Listing ID']])){

                $imagesPath = $structureDataImages[$estateInfo['Listing ID']]['Unit Thumb Path'];

                $iPaths = explode(';',$imagesPath);

                $imagesPath = json_encode($iPaths);

                $florplansPath = $structureDataImages[$estateInfo['Listing ID']]['Floorplans Path'];

                $fPaths = explode(';',$florplansPath);

                $florplansPath = json_encode($fPaths);

                $imagesPathLarge = $structureDataImages[$estateInfo['Listing ID']]['Unit Large Path'];

                $imagesPathLarge = explode(';',$imagesPathLarge);

                $imagesPathLarge = json_encode($imagesPathLarge);

            }else {

                $imagesPath = json_encode(array());

                $florplansPath = json_encode(array());

                $imagesPathLarge  = json_encode(array());

            }*/

            if (strlen($estateInfo['Unit #']) > 20){

                continue;

            }

            $districtId = $this->getDistrictsIdByName($estateInfo['Neighborhood Name'],$subDistricts);



            $agent = $this->getAgentInfo();

            $build = $this->getBuildingInfo($estateInfo['Building Address']);

            if($build){
                $buildingID = $build->building_id;
            }else{

                $building = Building::create([

                    'building_id' => mt_rand(10000000,99999999),

                    'building_address' => $estateInfo['Building Address'],

                    'building_name' => (string)utf8_encode(trim($estateInfo['Building Name'])),

                    'building_city' => $estateInfo['City'],

                    'building_state' => $estateInfo['State'],

                    'building_zip' => $estateInfo['Zip'],

                    'building_units' => $estateInfo['Building Unit Count'],

                    'building_stories' => (int)$estateInfo['Building Floors Stories'],

                    'building_build_year' => (int)$estateInfo['Building Year Built'],

                    'building_amenities' => $estateInfo['Building Amenities'],

                    'building_description' => '', // TODO no description in files

                    'path_for_building_images_on_s3' => '',

                    'building_images' => '',

                    'b_listing_type' => 2,
                    'building_type' => $typeId,
                    'building_district_id' => $districtId,
                    'name_label' => 0,
                    'amazon_id' => 0,
                    'neighborhood' => $estateInfo['Neighborhood Name'],

                ]);

                $buildingID = $building->building_id;
            }

            $es = $this->getRandomImages();

            if($es){

                $path_for_images = $es['0']->path_for_images;

                $path_for_large = $es['0']->path_for_large;

                $path_for_floorplans  = $es['0']->path_for_floorplans;

                $images = $es['0']->images;
            }else{
                $path_for_images = '';

                $path_for_large = '';

                $path_for_floorplans  = '';

                $images = '';
            }


//            if ((int)$estateInfo['Listing ID'] != 2268035){

//                continue;

//            }else {

//                echo('<pre>');

//                var_export($estateInfo);

//                echo('</pre>');

//                exit;

//            }

            $estate = EstateInfo::create([

                'active' => 1,

                'estate_type' => 2, // now parse Rentals

                'name' => (string)utf8_encode(trim($estateInfo['Building Name'])),

                'full_address' => $estateInfo['Building Address'],

                'address' => $estateInfo['Building Address'],

                'city' => $estateInfo['City'],

                'state' => $estateInfo['State'],

                'zip' => $estateInfo['Zip'],

                'units' => $estateInfo['Building Unit Count'], // now from building by id

                'stories' => '', // now from building by id

                'year_built' => $estateInfo['Building Year Built'], // now from building by id

                'building_type' => $estateInfo['Building Type'],

                'unit_type' => $estateInfo['Unit Type'],

                'type_id' => $typeId,

                'neighborhood' => $estateInfo['Neighborhood Name'],

                'district_id' => $districtId,

                'amenities' => $estateInfo['Unit Amenities'], //here only texts for display

                'b_amenities' => $estateInfo['Building Amenities'],

                'date' => date("Y-m-d H:i:s", strtotime($estateInfo['Date Available'])),

                'unit' =>  $estateInfo['Unit #'],

                'price' => floatval(str_replace(' ','',str_replace('$','',$estateInfo['Price Current']))),

                'beds' => floatval($estateInfo['Bed']),

                'baths' => floatval($estateInfo['Bath']),

                'room' => floatval($estateInfo['Rooms']),

                'sq_feet' =>  0, //TODO - no size for rentals floatval(str_replace(',','',str_replace('$','',$estateInfo['Size']))),

                'common_charges' => floatval(0), // TODO no common charges on new files

                'monthly_taxes' => 0,//TODO - no tax for rentals floatval(str_replace(',','',str_replace('$','',$estateInfo['Taxes']))),

                'maintenance' => 0, //No Maint for rentals floatval(str_replace(',','',str_replace('$','',$estateInfo['Maint']))),

                'agent_company' => $agent['0']->company,

                'agent_phone' => $agent['0']->office_phone,

                'unit_images' => '',

                'unit_description' => (string)utf8_encode(trim($estateInfo['Description'])),

                'building_id' => $buildingID,

                'path_for_images' => $path_for_images,

                'path_for_large' => $path_for_large,

                'path_for_floorplans' => $path_for_floorplans,

                'images' => $images, //TODO

                'listing_id' => (int)$estateInfo['Listing ID']


            ]);

            if ($estate->id) {

                //now we need store data for filters

                $estateAmnetiesIds['estate_id'] = $estate->id;

                $estateAmnetiesIds['f_354'] = 0;

                $estateAmnetiesIds['f_356'] = 0;

                $estateAmnetiesIds['f_357'] = 0;

                $estateAmnetiesIds['f_361'] = 0;

                $estateAmnetiesIds['f_362'] = 0;

                $estateAmnetiesIds['f_363'] = 0;

                $estateAmnetiesIds['f_365'] = 0;

                $estateAmnetiesIds['f_379'] = 0;

                $estateAmnetiesIds['f_384'] = 0;

                $estateAmnetiesIds['f_386'] = 0;

                $estateAmnetiesIds['f_387'] = 0;

                $estateAmnetiesIds['f_388'] = 0;

                $estateAmnetiesIds['f_389'] = 0;

                $estateAmnetiesIds['f_390'] = 0;

                $estateAmnetiesIds['f_391'] = 0;

                $estateAmnetiesIds['f_392'] = 0;

                $estateAmnetiesIds['f_393'] = 0;

                $estateAmnetiesIds['f_394'] = 0;

                $estateAmnetiesIds['f_395'] = 0;

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Playroom'))){

                    $estateAmnetiesIds['f_354'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Gym'))){

                    $estateAmnetiesIds['f_356'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Laundry'))){

                    $estateAmnetiesIds['f_357'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Pool'))){

                    $estateAmnetiesIds['f_361'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Storage'))){

                    $estateAmnetiesIds['f_362'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Roof Deck')

                )){

                    $estateAmnetiesIds['f_363'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Parking')

                )){

                    $estateAmnetiesIds['f_365'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Garage')

                )){

                    $estateAmnetiesIds['f_379'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Dishwasher')

                )){

                    $estateAmnetiesIds['f_384'] = 1;

                }

                if ($estateInfo['Building Year Built'] < 1945){

                    $estateAmnetiesIds['f_385'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Fireplace')

                )){

                    $estateAmnetiesIds['f_386'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Loft')

                )){

                    $estateAmnetiesIds['f_387'] = 1;

                }

                if (isset($estateInfo['Furnished']) && $estateInfo['Furnished']  == 'TRUE'){

                    $estateAmnetiesIds['f_388'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Outdoor Space')

                )){

                    $estateAmnetiesIds['f_389'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Washer')

                )){

                    $estateAmnetiesIds['f_390'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Doorman')

                )){

                    $estateAmnetiesIds['f_391'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Elevator')

                )){

                    $estateAmnetiesIds['f_392'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Unit Amenities']),mb_strtoupper('Elevator')

                )){

                    $estateAmnetiesIds['f_392'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Verizon')

                )){

                    $estateAmnetiesIds['f_393'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Unit Amenities']),mb_strtoupper('Pets Allowed'))

                    ||

                    strpos(mb_strtoupper($estateInfo['Unit Amenities']),mb_strtoupper('Pets OK'))){

                    $estateAmnetiesIds['f_394'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Unit Amenities']),mb_strtoupper('Washer')

                )){

                    $estateAmnetiesIds['f_395'] = 1;

                    $estateAmnetiesIds['f_390'] = 1;

                }

                EstateFilters::create($estateAmnetiesIds);

                //store connection agent with building

                $agent_id = $agent['0']->external_id;

                if(empty($agent_id) || $agent_id == NULL){
                    $agent_id = 2093111;
                }else{
                    $agent_id = $agent['0']->external_id;
                }

                AgentsListings::create([
                    'listings_id' => $estate->listing_id,
                    'agent_id' => $agent_id
                ]);

                $count++;

            }

            //fake listings

        }

        return $count;

    }

    public function parseEstateDataSales($structureData,$structureDataImages){

        /**  Images paths

         *'Listing ID' => '1319433',

        'Company' => 'One Irving Place Realty',

        'Clean Title' => '1 Irving Pl. #G11a',

        'Unit #' => '#G11A',

        'City' => 'Manhattan',

        'State' => 'NY',

        'Zip' => '10003',

        'Neighborhood Name' => 'Gramercy Park',

        'Size' => '740 ft??',

        'Maint' => '$823',

        'Path' => '/14728/1319433/',

        'Unit Large Path' => 'u_large/14728/1319433/302530757.jpg;u_large/14728/1319433/302531112.jpg;u_large/14728/1319433/302531192.jpg;u_large/14728/1319433/302531014.jpg;u_large/14728/1319433/302531316.jpg;u_large/14728/1319433/302531298.jpg;u_large/14728/1319433/302531324.jpg;u_l',

        'Unit Thumb Path' => 'u_thumb/14728/1319433/302530758.jpg;u_thumb/14728/1319433/302531114.jpg;u_thumb/14728/1319433/302531195.jpg;u_thumb/14728/1319433/302531016.jpg;u_thumb/14728/1319433/302531317.jpg;u_thumb/14728/1319433/302531299.jpg;u_thumb/14728/1319433/302531325.jpg;u_t',

        'Agent ID' => '2703244',

        'Agent Company URL' => '',

        'Phone' => '(212) 674 9150 x14',

        'Agent Company Name' => 'One Irving Place Realty',

        'Agent Images Path' => 'agent_image/14728/1319433/285658015.jpg',

        'Agent Name' => 'Ann Marie Mannino-Sarli',

        'Building ID' => '14728',

        'Building Year Built' => '1988',

        'Building Large Path' => 'b_large/14728/1319433/94645309.jpg;b_large/14728/1319433/12983329.jpg;b_large/14728/1319433/12983348.jpg;b_large/14728/1319433/12983351.jpg;b_large/14728/1319433/12983359.jpg;b_large/14728/1319433/2993099.jpg;b_large/14728/1319433/94645321.jpg;b_large/147',

        'Building Thumb Path' => 'b_thumb/14728/1319433/94645310.jpg;b_thumb/14728/1319433/12983340.jpg;b_thumb/14728/1319433/12983350.jpg;b_thumb/14728/1319433/12983352.jpg;b_thumb/14728/1319433/12983360.jpg;b_thumb/14728/1319433/2993099.jpg;b_thumb/14728/1319433/94645323.jpg;b_thumb/147',

        'Unit Amenities' => 'Elevator,Full-time Doorman,Pets OK',

        'Building Amenities' => 'Children\'s Playroom,Concierge,Gym,Laundry in Bldg,Green Building,Live-in Super,Package Room,Parking,Pool,Smoke-free,Storage Available,Verizon FiOS',

        'Floorplans Path' => 'floor_plan/14728/1319433/9a7924e9ecb5d1541276af00b25e021828402d7b.jpg',

        'Taxes' => '1000',

        'Alert' => 'TRUE',

         */

        /*  Estate info

         *   1319433 =>

  array (

    'Listing ID' => '1319433',

    'Company' => 'One Irving Place Realty',

    'Company ID' => '450',

    'Title' => '1 Irving Place #G11A',

    'Unit Description' => '"Prime location - Renovated, bright and sunny corner  one bedroom with South and East exposures

Windowed kitchen, hardwood floors, marble bath

In move in condition

Call to view with Resident Broker

Zeckendorf Towers is a full service luxury condom"',

    'Bed' => '1',

    'Bath' => '1',

    'Half Baths' => '0',

    'Rooms' => '3',

    'Street Address' => '1 Irving Place',

    'Unit #' => '#G11A',

    'Address Cross' => '',

    'City' => 'Manhattan',

    'State' => 'NY',

    'Zip' => '10003',

    'Longitude' => '-73.98919678',

    'Latitude' => '40.73450089',

    'Neighborhood Name' => 'Gramercy Park',

    'Size' => '740 ft??',

    'PPSF' => '$1,858 per ft??',

    'Maint' => '$823',

    'Listed' => '18/01/2018 20:08',

    'Unit Type' => 'Condo',

    'Price Current' => '$1,375,000',

    'Agent ID' => '2703244',

    'Agent Company URL' => '',

    'Agent Name' => 'Ann Marie Mannino-Sarli',

    'Phone' => '(212) 674 9150 x14',

    'Agent Company Name' => 'One Irving Place Realty',

    'Building ID' => '14728',

    'Building Name' => 'Zeckendorf Towers',

    'Building Address' => '1 IRVING PLACE',

    'Building Type' => 'Condo',

    'Building Active Sales' => '5',

    'Building Active Rentals' => '12',

    'Building Pending Sales Count' => '3',

    'Building Pending Rentals Count' => '4',

    'Building Closed Sales Count' => '251',

    'Building Closed Rentals Count' => '890',

    'Building Floors Stories' => '27',

    'Building Year Built' => '1988',

    'Building Unit Count' => '650',

    'Unit Amenities' => 'Elevator,Full-time Doorman,Pets OK',

    'Building Amenities' => 'Children\'s Playroom,Concierge,Gym,Laundry in Bldg,Green Building,Live-in Super,Package Room,Parking,Pool,Smoke-free,Storage Available,Verizon FiOS',

    'Is Featured' => 'FALSE',

    'Last Price Change Amount' => '0',

    'Last Price Change Date' => '',

    'Taxes' => '1000',

    'Alert' => 'TRUE',

    '' => '',

  ),

         */

        $count = 0;

        $filters_data = DB::table('filters')

            ->leftjoin('filters_data', 'filters.name', '=', 'filters_data.filter_id')

            ->get();

        $types = array();

        $subDistricts = array();

        $amneties = array();

        foreach ($filters_data as $fil){

            if ($fil->name=='sub_districts'){

                $subDistricts[] = $fil;

            }

            if ($fil->name == 'type'){

                $types[] = $fil;

            }

            if ($fil->name == 'filters'){

                $amneties[] = $fil;

            }

        }

        //start

        foreach ($structureData as $estateInfo){

            if (empty($estateInfo['Listing ID'])){

                continue;

            }



            $isset = EstateInfo::where('listing_id',$estateInfo['Listing ID'])->first();

            if ($isset){

                continue;

            }



            $buildingType = $estateInfo['Unit Type'];

            $typeId = $this->getTypeIdByType($buildingType);

            if (isset($structureDataImages[$estateInfo['Listing ID']])){

                $imagesPath = $structureDataImages[$estateInfo['Listing ID']]['Unit Thumb Path'];

                $iPaths = explode(';',$imagesPath);

                $imagesPath = json_encode($iPaths);

                $florplansPath = $structureDataImages[$estateInfo['Listing ID']]['Floorplans Path'];

                $fPaths = explode(';',$florplansPath);

                $florplansPath = json_encode($fPaths);

                $imagesPathLarge = $structureDataImages[$estateInfo['Listing ID']]['Unit Large Path'];

                $imagesPathLarge = explode(';',$imagesPathLarge);

                $imagesPathLarge = json_encode($imagesPathLarge);



            }else {

                $imagesPath = json_encode(array());

                $florplansPath = json_encode(array());

                $imagesPathLarge = json_encode(array());

            }

            if (strlen($estateInfo['Unit #']) > 20){

                continue;

            }

            $districtId = $this->getDistrictsIdByName($estateInfo['Neighborhood Name'],$subDistricts);

            $estate = EstateInfo::create([

                'active' => 1,

                'estate_type' => 1, // now parse Sales

                'name' => (string)utf8_encode(trim($estateInfo['Building Name'])),

                'full_address' => $estateInfo['Street Address'],

                'address' => $estateInfo['Street Address'],

                'city' => $estateInfo['City'],

                'state' => $estateInfo['State'],

                'zip' => $estateInfo['Zip'],

                'units' => $estateInfo['Building Unit Count'], // now from building by id

                'stories' => '', // now from building by id

                'year_built' => $estateInfo['Building Year Built'], // now from building by id

                'building_type' => $estateInfo['Building Type'],

                'unit_type' => $estateInfo['Unit Type'],

                'type_id' => $typeId,

                'neighborhood' => $estateInfo['Neighborhood Name'],

                'district_id' => $districtId,

                'amenities' => $estateInfo['Unit Amenities'], //here only texts for display

                'b_amenities' => $estateInfo['Building Amenities'],

                'date' => $estateInfo['Listed'],

                'unit' =>  $estateInfo['Unit #'],

                'price' => floatval(str_replace(',','',str_replace('$','',$estateInfo['Price Current']))),

                'beds' => floatval($estateInfo['Bed']),

                'baths' => floatval($estateInfo['Bath']),

                'room' => floatval($estateInfo['Rooms']),

                'sq_feet' => floatval(str_replace(',','',str_replace('$','',$estateInfo['Size']))),

                'common_charges' => floatval(0), // TODO no common charges on new files

                'monthly_taxes' => floatval(str_replace(',','',str_replace('$','',$estateInfo['Taxes']))),

                'maintenance' => floatval(str_replace(',','',str_replace('$','',$estateInfo['Maint']))),

                'agent_company' => $estateInfo['Agent Company Name'],

                'agent_phone' => (string)utf8_encode(trim($estateInfo['Phone'])),

                'unit_images' => '',

                'unit_description' => (string)utf8_encode(trim($estateInfo['Unit Description'])),

                'building_id' => floatval($estateInfo['Building ID']),

                'path_for_images' => $imagesPath,

                'path_for_large' => $imagesPathLarge,

                'path_for_floorplans' => $florplansPath,

                'images' => $imagesPath, //TODO

                'listing_id' => (int)$estateInfo['Listing ID'],

            ]);

            if ($estate->id) {

                //now we need store data for filters

                $estateAmnetiesIds['estate_id'] = $estate->id;

                $estateAmnetiesIds['f_354'] = 0;

                $estateAmnetiesIds['f_356'] = 0;

                $estateAmnetiesIds['f_357'] = 0;

                $estateAmnetiesIds['f_361'] = 0;

                $estateAmnetiesIds['f_362'] = 0;

                $estateAmnetiesIds['f_363'] = 0;

                $estateAmnetiesIds['f_365'] = 0;

                $estateAmnetiesIds['f_379'] = 0;

                $estateAmnetiesIds['f_384'] = 0;

                $estateAmnetiesIds['f_386'] = 0;

                $estateAmnetiesIds['f_387'] = 0;

                $estateAmnetiesIds['f_388'] = 0;

                $estateAmnetiesIds['f_389'] = 0;

                $estateAmnetiesIds['f_390'] = 0;

                $estateAmnetiesIds['f_391'] = 0;

                $estateAmnetiesIds['f_392'] = 0;

                $estateAmnetiesIds['f_393'] = 0;

                $estateAmnetiesIds['f_394'] = 0;

                $estateAmnetiesIds['f_395'] = 0;

                /*

                                 *

                                 *

                                 *

                                 * 354

                 * 	Children play room

                356	Gym

                357	Laundry in Building

                361	Swimming Pool

                362	Storage Available

                363	Roof Deck



                365	Parking Available

                379	Garage Parking



                384	Dishwasher

                385	Prewar (built before 1945)

                386	Fireplace

                387	Loft

                388	Furnished



                389	Public or Private Outdoor Space





                390	Washer/Dryer In-Unit

                391	Doorman

                392	Elevator

                393	Verizon FiOS Enabled

                394	Pets Allowed

                395	Pied-a- Terre Allowed



                                 */

//Children's Playroom,Concierge,Gym,Laundry in Bldg,Green Building,Live-in Super,Package Room,Parking,Pool,Smoke-free,Storage Available,Verizon FiOS

//Storage Available,Virtual Doorman,Verizon FiOS

//Garage Parking,Gym,Parking

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Playroom'))){

                    $estateAmnetiesIds['f_354'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Gym'))){

                    $estateAmnetiesIds['f_356'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Laundry'))){

                    $estateAmnetiesIds['f_357'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Pool'))){

                    $estateAmnetiesIds['f_361'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Storage'))){

                    $estateAmnetiesIds['f_362'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Roof Deck')

                )){

                    $estateAmnetiesIds['f_363'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Parking')

                )){

                    $estateAmnetiesIds['f_365'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Garage')

                )){

                    $estateAmnetiesIds['f_379'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Dishwasher')

                )){

                    $estateAmnetiesIds['f_384'] = 1;

                }

                if ($estateInfo['Building Year Built'] < 1945){

                    $estateAmnetiesIds['f_385'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Fireplace')

                )){

                    $estateAmnetiesIds['f_386'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Loft')

                )){

                    $estateAmnetiesIds['f_387'] = 1;

                }

                if (isset($estateInfo['Furnished']) && $estateInfo['Furnished']  == 'TRUE'){

                    $estateAmnetiesIds['f_388'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Outdoor Space')

                )){

                    $estateAmnetiesIds['f_389'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Washer')

                )){

                    $estateAmnetiesIds['f_390'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Doorman')

                )){

                    $estateAmnetiesIds['f_391'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Unit Amenities']),mb_strtoupper('Elevator')

                )){

                    $estateAmnetiesIds['f_392'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Building Amenities'].$estateInfo['Unit Amenities']),mb_strtoupper('Verizon')

                )){

                    $estateAmnetiesIds['f_393'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Unit Amenities']),mb_strtoupper('Pets Allowed'))

                    ||

                    strpos(mb_strtoupper($estateInfo['Unit Amenities']),mb_strtoupper('Pets OK'))){

                    $estateAmnetiesIds['f_394'] = 1;

                }

                if (strpos(mb_strtoupper($estateInfo['Unit Amenities']),mb_strtoupper('Washer')

                )){

                    $estateAmnetiesIds['f_395'] = 1;

                }

                EstateFilters::create($estateAmnetiesIds);

                //store connection agent with building

                $count++;

            }

            //fake listings

        }

        return $count;

    }

    public function parseAgents( Request $request){

        // $path1 = 'F:\OpenServer\domains\realestate.local\rentals_imgs_csv.csv';

        $path1 = 'F:\OpenServer\domains\realestate.local\sales_imgs_csv.csv';



        if (isset($_GET['rent'])){

            $path1 = 'F:\OpenServer\domains\realestate.local\rentals_imgs_csv.csv';

        }

        $data = @file_get_contents($path1);

        if (!$data){

            echo('wrong path '.$path1);

            exit;

        }

        set_time_limit(0);

        ignore_user_abort(true);

//        DB::table('estate_data')->truncate();

//        DB::table('estate_filters')->truncate();

        $structureData = array();



        if ($data){

            $count = 0;

            $estates = explode("DELIMITERDELIMETER",$data);

            $dataStruct = array();

            $struct1 = array();

            foreach ($estates as $key=>$estateLine) {

                if ($key == 0) {

                    $dataStruct = explode("|", $estateLine);

                    foreach ($dataStruct as $columnName) {

                        $struct1[trim($columnName)] = '';

                    }

                    continue;

                }

                $estateInfo = explode("|",$estateLine);

                $ii=0;

                foreach ($struct1 as $keyName=>$v){

                    if (!isset($estateInfo[$ii])){

                        continue;

                    }

                    $struct1[$keyName] = trim($estateInfo[$ii]);

                    $ii++;

                }

                if (isset($struct1['Agent ID'])){

                    $structureData[$struct1['Agent ID'].'_'.$struct1['Listing ID']]= $struct1;

                }

            }

        }



        $structureDataImages = array();

        // $path1 = 'F:\OpenServer\domains\realestate.local\rentals_csv.csv';

        $path1 = 'F:\OpenServer\domains\realestate.local\sales_csv.csv';



        if (isset($_GET['rent'])){

            $path1 = 'F:\OpenServer\domains\realestate.local\rentals_csv.csv';

        }

        $data = @file_get_contents($path1);

        if (!$data){

            echo('wrong path images'.$path1);

            exit;

        }

        set_time_limit(0);

        ignore_user_abort(true);

//        DB::table('estate_data')->truncate();

//        DB::table('estate_filters')->truncate();



        if ($data){

            $count = 0;

            $estates = explode("DELIMITERDELIMETER",$data);

            $dataStruct = array();

            $struct1 = array();

            foreach ($estates as $key=>$estateLine) {

                if ($key == 0) {

                    $dataStruct = explode('|', $estateLine);

                    foreach ($dataStruct as $columnName) {

                        $struct1[trim($columnName)] = '';

                    }

                    continue;

                }

                $estateInfo = explode('|',$estateLine);

                $ii=0;

                foreach ($struct1 as $keyName=>$v){

                    if (!isset($estateInfo[$ii])){

                        continue;

                    }

                    $struct1[$keyName] = trim($estateInfo[$ii]);

                    $ii++;

                }

                $structureDataImages[$struct1['Agent ID'].'_'.$struct1['Listing ID']]= $struct1;

            }

        }

        if ( $structureData){

            $count = 0;

            foreach ($structureData as $key=> $row){

                if (!isset($structureDataImages[$row['Agent ID'].'_'.$row['Listing ID']])){

                    $companyUrl =  '';

                    $phone = '';

                }else {

                    $companyUrl =  $structureDataImages[$row['Agent ID'].'_'.$row['Listing ID']]['Agent Company URL'];

                    $phone = $structureDataImages[$row['Agent ID'].'_'.$row['Listing ID']]['Phone'];

                }





                $agentId = (int)$row['Agent ID'];

                if ($agentId == 0){

                    //Thisis 0 agent id.

                    $agentId = $row['Listing ID']+time();

                }

                if (!AgentsListings::where('listings_id',$row['Listing ID'])

                    ->where('agent_id',$agentId)->where('listings_id', (int)$row['Listing ID'])->first()){

                    AgentsListings::create([

                        'listings_id' => (int)$row['Listing ID'],

                        'agent_id' => $agentId

                    ]);

                }

                //store to DB

                if (!AgentsBuildings::where('building_id',$row['Building ID'])

                    ->where('agent_id',$agentId)->where('building_id', (int)$row['Building ID'])->first()){

                    AgentsBuildings::create([

                        'building_id' => (int)$row['Building ID'],

                        'agent_id' => (int)$agentId

                    ]);

                }









                //store agents

                if (!AgentInfo::where('external_id',$agentId)

                    ->first()){

                    $name = $row['Agent Name'];

                    $name = explode(' ',$name);

                    $lastName =  isset($name[1]) ? $name[1] : '';

                    $firstName = $name[0];

                    AgentInfo::create([

                        'user_id' => 0,

                        'last_name' => $lastName,

                        'first_name' => $firstName,

                        'photo' => isset($row['Agent Picture Path']) ? $row['Agent Picture Path'] : $row['Agent Images Path'],

                        'photo_url' => isset($row['Agent Picture Path']) ? $row['Agent Picture Path'] : $row['Agent Images Path'],

                        'company' => $row['Agent Company Name'],

                        'web_site' => $companyUrl,

                        'office_phone' => (string)utf8_encode(trim($phone)),

                        'fax' =>  (string)utf8_encode(trim($phone)),

                        'description' => '',

                        'external_id' => $agentId,

                    ]);

                }

                $count++;

            }

            echo('Loaded '.$count);

            exit;

        }else {





            echo('not load');

            exit;

        }

    }

    public function parsebuild( Request $request){

        $path1 = 'F:\OpenServer\domains\realestate.local\rentals_path.csv';

        $data = @file_get_contents($path1);

        if (!$data){

            echo('wrong path '.$path1);

            exit;

        }

        set_time_limit(0);

        ignore_user_abort(true);

//        DB::table('estate_data')->truncate();

//        DB::table('estate_filters')->truncate();

        $structureData = array();

        if (!$data){

            echo('not load');

            exit;

        }

        if ($data){

            $count = 0;

            $estates = explode("DELIMITERDELIMETER",$data);

            $dataStruct = array();

            $struct1 = array();

            foreach ($estates as $key=>$estateLine) {

                if ($key == 0) {

                    $dataStruct = explode("|", $estateLine);

                    foreach ($dataStruct as $columnName) {

                        $struct1[trim($columnName)] = '';

                    }

                    continue;

                }

                $estateInfo = explode("|",$estateLine);

                $ii=0;

                foreach ($struct1 as $keyName=>$v){

                    if (!isset($estateInfo[$ii])){

                        continue;

                    }

                    $struct1[$keyName] = trim($estateInfo[$ii]);

                    $ii++;

                }

                if (isset($struct1['Building ID'])){

                    $structureData[$struct1['Building ID']]= $struct1;

                }

            }

        }



        $structureDataImages = array();

        $path1 = 'F:\OpenServer\domains\realestate.local\rentals_info.csv';

        $data = @file_get_contents($path1);

        if (!$data){

            echo('wrong path images'.$path1);

            exit;

        }

        set_time_limit(0);

        ignore_user_abort(true);

//        DB::table('estate_data')->truncate();

//        DB::table('estate_filters')->truncate();

        if (!$data){

            echo('not load');

            exit;

        }

        if ($data){

            $count = 0;

            $estates = explode("DELIMITERDELIMETER",$data);

            $dataStruct = array();

            $struct1 = array();

            foreach ($estates as $key=>$estateLine) {

                if ($key == 0) {

                    $dataStruct = explode('|', $estateLine);

                    foreach ($dataStruct as $columnName) {

                        $struct1[trim($columnName)] = '';

                    }

                    continue;

                }

                $estateInfo = explode('|',$estateLine);

                $ii=0;

                foreach ($struct1 as $keyName=>$v){

                    if (!isset($estateInfo[$ii])){

                        continue;

                    }

                    $struct1[$keyName] = trim($estateInfo[$ii]);

                    $ii++;

                }

                $structureDataImages[$struct1['Building ID']]= $struct1;

            }

        }



        if ( $structureData){

            $count = 0;

            foreach ($structureData as $key=> $row){

                if (!isset($structureDataImages[$row['Building ID']])){

                    continue;

                }

                $extraInfo = $structureDataImages[$row['Building ID']];

                $bName = $extraInfo['Building Name'];

                $bAddress = $extraInfo['Building Address'];

                $bUnitCount =  $extraInfo['Building Unit Count'];

                $bFloorStories =  $extraInfo['Building Floors Stories'];

                $imgs = $row['Building Thumb Path'];

                $imgs = explode(';',$imgs);

                $imgs = json_encode($imgs);

                if (Building::where('building_id',$row['Building ID'])->first()){

                    continue;

                }

                //store to DB

                $building = Building::create([

                    'building_id' => $row['Building ID'],

                    'building_address' => $bAddress,

                    'building_name' => $bName,

                    'building_city' => $row['City'],

                    'building_state' => $row['State'],

                    'building_zip' => $row['Zip'],

                    'building_units' => (int)$bUnitCount,

                    'building_stories' => (int)$bFloorStories,

                    'building_build_year' => (int)$extraInfo['Building Year Built'],

                    'building_amenities' => $extraInfo['Building Amenities'],

                    'building_description' => '', // TODO no description in files

                    'path_for_building_images_on_s3' => $imgs,

                    'building_images' => $imgs,

                ]);

                $count++;

            }

            echo('Loaded '.$count);

            exit;

        }else {

            echo('not load');

            exit;

        }

    }

    public function parsebuildOld( Request $request){

        //$fileDir = '/var/www/estate-dev.gabsoft.net/estates_raw3csv.csv';

        $path = $request->get('path');

        //$path = 'F:\OpenServer\domains\realestate.local\hyporealestate\buildings50.csv';

        $data = @file_get_contents($path);

        if (!$data){

            echo('wrong path '.$path);

            exit;

        }

        set_time_limit(0);

        ignore_user_abort(true);

        if ($data){

            $rows = explode("\n",$data);

            $count = 0;

            foreach ($rows as $key=> $row){

                if ($key==0){

                    continue;

                }

                $buildInfo = explode(';',$row);

                /*

                  0 => 'building_id',

                  1 => 'address',

                  2 => 'building_name',

                  3 => 'building_city',

                  4 => 'building_state',

                  5 => 'building_zip',

                  6 => 'building_units',

                  7 => 'building_stories',

                  8 => 'building_build_year',

                  9 => 'building_amenities',

                  10 => '',

                  11 => 'path_for_building_images_on_s3

                 */

                if (!isset($buildInfo[11])){

                    $buildInfo[11] = "[]";

                }

                if (!isset($buildInfo[1])){

                    continue;

                }

                $imgs = $buildInfo[11];

                $imgs = str_replace("\'","'",$imgs);

                $imgs = str_replace("']",'',$imgs);

                $imgs = str_replace("['",'',$imgs);

                $imgs = explode(',',$imgs);

                foreach ($imgs as &$img){

                    $img = trim($img);

                    $img = trim($img,"'");

                }

                $imgs = json_encode($imgs);

                //store to DB

                $building = Building::create([

                    'building_id' => $buildInfo[0],

                    'building_address' => $buildInfo[1],

                    'building_name' => $buildInfo[2],

                    'building_city' => $buildInfo[3],

                    'building_state' => $buildInfo[4],

                    'building_zip' => $buildInfo[5],

                    'building_units' => (int)$buildInfo[6],

                    'building_stories' => $buildInfo[7],

                    'building_build_year' => (int)$buildInfo[8],

                    'building_amenities' => $buildInfo[9],

                    'building_description' => $buildInfo[10],

                    'path_for_building_images_on_s3' => $buildInfo[11],

                    'building_images' => $imgs,

                ]);

                $count++;

            }

            echo('Loaded '.$count);

            exit;

        }else {

            echo('not load');

            exit;

        }

    }

    public function getDistrictsIdByName($districtName,$districts){

        $districtId = 0;

        foreach ($districts as $d){

            if (trim(mb_strtoupper($districtName)) == trim(mb_strtoupper($d->value))){

                $districtId = $d->filter_data_id;

            }

        }

        return $districtId;

    }

    public function getTypeIdByType($buildingType){

        $typeId = 0;

        /**

         *

         * Building

        Multi-family

        Condo

        Co-op

        Townhouse

        Co-op

        House

         *

         */

        if($buildingType=='Condo'){

            $typeId = 6;

        }

        if($buildingType=='Co-op' || $buildingType =='Coop'){

            $typeId = 7;

        }

        if($buildingType=='Townhouse' || $buildingType =='Town house' || strpos($buildingType,'family')){

            $typeId = 8;

        }

        if($buildingType=='Multi-family' || $buildingType =='Multi family'){

            $typeId = 9;

        }



//        UPDATE estate_data SET `type_id` = 6 WHERE `unit_type` LIKE '%Condo%' OR unit_type LIKE '%condo%';

//        UPDATE estate_data SET `type_id` = 7 WHERE `unit_type` LIKE '%Co-op%' OR unit_type LIKE '%Coop%' OR unit_type LIKE '%coop%';

//        UPDATE estate_data SET `type_id` = 8 WHERE `unit_type` LIKE '%House%' OR unit_type LIKE '%Townhouse%' OR unit_type LIKE '%Family Home%';

//        UPDATE estate_data SET `type_id` = 9 WHERE `unit_type` LIKE '%Multi%' OR unit_type LIKE '%multi%';

        //TODO -- maybe add more building Types ??

        // now we have not filters for somes



        return $typeId;

    }

    public function spamClean(){

        $estates = EstateInfo::all();

        foreach ($estates as $estate){

            $estate->unit_description = str_replace('_x000D_','',$estate->unit_description );

            $estate->save();

        }
    }

    public function parseDistrict(Request $request){

        /*$path1 = 'C:\OSPanel\domains\hypro.loc\manhattan.txt';

        $fn = fopen($path1,"r");

        while(! feof($fn))  {
            $result = fgets($fn);

            DB::table('district')->insert([
                'name' => trim($result),
                'parent_id' => 5,
                'district_id' => 0
                ]);
        }

        fclose($fn);*/

        /*$data = $filters_data = DB::table('district')
            ->get();

        foreach ($data as $item){
            DB::table('filters_data')->insert([
                'filter_id' => 'sub_districts',
                'value' => $item->name,
                'parent_id' => $item->parent_id,
                'district_id' => $item->district_id
            ]);

        }*/

        //change district id for old listing
        /*$estate = EstateInfo::all();

        $filters = DB::table('filters_data')->get();

        foreach($estate as $item){
            foreach ($filters as $value){
                if($item->neighborhood == $value->value){
                    $item->district_id = $value->filter_data_id;
                    $item->save();
                }
            }
        }
        echo "done";*/

        //add data to building table
       $estate = EstateInfo::all();
/*
        foreach($estate as $item){
            $buiding = Building::where('building_address',$item->full_address)
                ->update(['building_type' => $item->type_id,
                          'building_district_id' => $item->district_id,
                          'neighborhood' => $item->neighborhood]);
        }

        echo "done";*/



    }
}
