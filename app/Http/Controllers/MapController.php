<?php

namespace App\Http\Controllers;

use App\Repositories\MapRepo;
use Illuminate\Http\Request;

class MapController extends Controller
{
    private $map;

    public function __construct(MapRepo $map)
    {
        $this->map = $map;
    }

    public function jsonPolygon(Request $request){

        if(file_exists(public_path('geojson/' . $request->get('file') . '.geojson'))){
            $jsondata = file_get_contents(public_path('geojson/' . $request->get('file') . '.geojson'));

            $jsondata = json_decode($jsondata);

            $geometry = $jsondata->features[0]->geometry;

            return json_encode(['success' => true, 'geometry' => $geometry]);
        } else {
            return json_encode(['success' => false]);
        }
    }
}
