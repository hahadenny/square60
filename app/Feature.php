<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $table = 'features';

    protected $fillable = [
        'listing_id', 'user_id', 'renew', 'ends_at', 'period', 'amount', 'status'
    ];
}
