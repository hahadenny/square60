<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgentsBuildings extends Model
{
    protected $table = 'agents_buildings';

    protected $fillable = [
        'agent_id', 'building_id'
    ];


    public function agent()
    {
        return $this->belongsTo('App\AgentInfo','external_id','agent_id');
    }
}