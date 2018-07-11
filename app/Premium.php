<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Premium extends Model
{
    protected $table = 'premiums';

    protected $fillable = [
        'listing_id', 'user_id', 'renew', 'ends_at', 'period', 'amount', 'status'
    ];
}
