<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgentsListings extends Model
{
    protected $table = 'agents_listings';

    protected $fillable = [
        'agent_id', 'listings_id'
    ];


    public function agent()
    {
        return $this->hasOne('App\AgentInfo','external_id','agent_id');
    }
}