<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SearchResults extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'search_results';
    protected $fillable = [
       'ids',
       'request',
       'count',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class,'user_search_results', 'search_id','user_id');
    }
}