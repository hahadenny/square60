<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Failed extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'failed';
    protected $fillable = [
       'user_id', 'refer_id', 'type', 'error', 'amount', 'period', 'renew'
    ];
}