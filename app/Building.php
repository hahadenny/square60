<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    //

    protected $fillable = [
        'building_id', 'building_address', 'building_name' , 'building_city', 'building_state', 'building_zip',
        'building_units', 'building_stories', 'building_build_year', 'building_amenities',
        'building_description', 'path_for_building_images_on_s3', 'building_images', 'b_listing_type', 'name_label_image', 'path_for_name_label_image'
    ];

    public function filterType()
    {
        return $this->hasOne('App\FiltersData','filter_data_id', 'building_type');
    }

}
