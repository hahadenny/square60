<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FiltersData extends Model
{
    protected $table = 'filters_data';

    protected $fillable = [
        'filter_data_id', 'filter_id', 'value', 'parent_id', 'district_id', 'created_at', 'updated_at'
    ];
}
