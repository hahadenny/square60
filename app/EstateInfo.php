<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstateInfo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'estate_data';




    protected $fillable = [
        'active',        'estate_type',        'name',        'full_address',        'address',
        'city','state','zip','images','units','stories','year_built','building_type','unit_type', 'type_id',
        'neighborhood','district_id', 'amenities', 'b_amenities', 'date','unit','price','last_price','beds','baths','sq_feet',
        'common_charges','monthly_taxes','maintenance','agent_company','agent_phone',
        'unit_images','unit_description', 'building_id', 'path_for_images', 'path_for_large','path_for_floorplans', 'listing_id', 'condition'
    ];


    public function districts(array $districts){
        if (count($districts)){
        return $this->whereIn('distritct_id', $districts);
        }else  {
            return $this;
        }
    }

    public function types(array $types,$query){
        if (count($types)){
        return $this->whereIn('distritct_id', $types);
        }else {
            return $this;
        }
    }

    public function building()
    {
        return $this->hasOne('App\Building','building_id','building_id');
    }

    public function images()
    {
        return $this->hasOne('App\EstateImages','es_listing_id','listing_id');
    }

    public function logo()
    {
        return $this->hasOne('App\LogoPath','listing_id', 'listing_id');
    }

    public function openHouse()
    {
        return $this->hasMany('App\OpenHouse','listing_id', 'id');
    }

    public function features()
    {
        return $this->hasMany('App\Feature','listing_id', 'id');
    }

    public function premiums()
    {
        return $this->hasMany('App\Premium','listing_id', 'id');
    }

}