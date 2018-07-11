<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstateImages extends Model
{
    protected $table = 'estate_data_images';

    protected $fillable = [
        'es_listing_id', 'unit_path', 'agent_path',	'unit_thumb'
    ];
}
