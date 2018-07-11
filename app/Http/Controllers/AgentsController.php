<?php

namespace App\Http\Controllers;

use App\Repositories\AgentRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgentsController extends Controller
{
    private $agent;

    public function __construct(AgentRepo $agent)
    {
        $this->agent = $agent;
    }

    public function profile($name='', $agent_id = 0){

        $agent = $this->agent->getAgentProfile($agent_id);

        $estates = $this->agent->getAgentEstates($agent_id);

        return view('profileAgent', compact('agent', 'estates'));
    }

    public function agentsList(Request $request){

        $request->user()->authorizeRoles(['admin']);

        $agentsList = $this->agent->agentsList();

        return view('showAgents', compact('agentsList'));
    }

    public function edit(Request $request){

        $request->user()->authorizeRoles(['admin']);

        $agent = $this->agent->getAgent($request->id);

        $photo = $this->agent->getAgentPhoto($agent);

        return view('editAgents', compact('agent','photo'));
    }

    public function update(Request $request){

        $request->user()->authorizeRoles(['admin', 'Agent']);

        $validator = Validator::make($request->all(), [
            'lastName' => 'required|min:3',
            'firstName' => 'required|min:3',
            'company' => 'required|min:3',
            'webLink' => 'required|min:3',
            'officePhone' => 'required',
            'fax' => 'required',
            'description' => 'min:5',
        ]);

        if ($validator->fails())
        {
            return redirect('/agentedit')
                ->withErrors($validator)
                ->withInput();

        }

        if ($this->agent->updateAgent($request)){
            return redirect('/agents')->with('status', 'Agent update');
        }else{
            return redirect('/agents')->with('status', 'Can not update, try again');
        }

    }

    public function delete(Request $request){

        $agent = $this->agent->deleteAgent($request); //todo delete photo from storage when all be in one storage

        if ($agent){
            return redirect('/agents')->with('status', 'Agent deleted');
        }else{
            return redirect('/agents')->with('status', 'Can not delete, try again');
        }
    }

    public function deleteImage(Request $request){

        if($this->agent->deleteAgentImages($request)){
            return 'Image delete';
        }else{
            return 'Can not delete, try again';
        }
    }
}
