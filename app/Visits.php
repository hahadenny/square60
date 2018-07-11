<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visits extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'visits';
    public $timestamps = false;
    protected $fillable = [
       'day', 'visits', 'location', 'url'
    ];
}