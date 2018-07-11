<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $table = 'memberships';

    protected $fillable = [
        'user_id', 'renew', 'ends_at', 'period', 'amount', 'type', 'status'
    ];    
}
