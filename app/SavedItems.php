<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SavedItems extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'saved_items';
    protected $fillable = [
       'user_id', 'save_id', 'type', 'created_at'
    ];
}