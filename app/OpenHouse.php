<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OpenHouse extends Model
{
    protected $table = 'estate_open_houses';

    protected $fillable = [
        'listing_id', 'date', 'start_time', 'end_time', 'appointment'
    ];
}
