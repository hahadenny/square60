<?php

namespace App\Http\Controllers;

use App\Repositories\SaveSearchRepo;
use Illuminate\Http\Request;

class SaveSearchController extends Controller
{
    private $saveSeach;

    public function __construct(SaveSearchRepo $save)
    {
        $this->saveSeach = $save;
    }

    public function index(Request $request)
    {
       $request->user()->authorizeRoles(['Owner', 'Agent', 'user', 'man']);

       $saveSearch = $this->saveSeach->getSaveSearch();

       $savedItems = $this->saveSeach->getSavedItems();

       return view('saveSearchListing')->with($saveSearch)->with($savedItems);
    }

    public function store(Request $request)
    {
        $request->user()->authorizeRoles(['Owner', 'Agent', 'user', 'man']);

        return $this->saveSeach->saveSearch($request);
    }

    public function show(Request $request)
    {
        $request->user()->authorizeRoles(['Owner', 'Agent','user', 'man']);

        return $this->saveSeach->getSaveResult($request->user_id);
    }

    public function delete(Request $request)
    {
        $request->user()->authorizeRoles(['Owner', 'Agent','user', 'man']);

        if($this->saveSeach->deleteSaveSearch($request->user()->id, $request->search)){
            return redirect()->back()->with('status', 'Search deleted.');
        }else{
            return redirect()->back()->with('status', 'Failed to deleted');
        }
    }

    public function deleteItem(Request $request)
    {
        $request->user()->authorizeRoles(['Owner', 'Agent','user', 'man']);

        if($this->saveSeach->deleteItem($request->user()->id, $request->sid)){
            return redirect()->back()->with('status', 'Item deleted.');
        }else{
            return redirect()->back()->with('status', 'Failed to deleted');
        }
    }
}
