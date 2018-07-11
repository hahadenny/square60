<?php
namespace App\Http\Controllers;

use App\Repositories\ShowRepo;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ShowController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $showRepo;

    public function __construct(ShowRepo $showRepo)
    {
        $this->showRepo = $showRepo;
    }

    public function show(Request $request,$name, $id)
    {   
        $result = $this->showRepo->estate->getListing($id);
        
        if (!$result || (!$result->active)){
            return view('404');
        }
        else
            return view('show2')->with($this->showRepo->show($request, $name, $id));
    }

    public function save(Request $request) {
        //print_r($request->name); exit;
        $result = $this->showRepo->saveListing($request);
        
        if (!$result){
            return redirect('/show/'.$request->name.'/'.$request->save_id)->with('status', 'Listing already saved.');
        }
        else
            return redirect('/show/'.$request->name.'/'.$request->save_id)->with('status', 'Listing saved successfully!');
    }
}