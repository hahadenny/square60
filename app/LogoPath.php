<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogoPath extends Model
{
    protected $table = 'logo_path';

    protected $fillable = [
        'listing_id', 'logo_path'
    ];



}
