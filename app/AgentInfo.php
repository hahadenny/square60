<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class AgentInfo extends Model
{
    protected $table = 'agent_infos';

    protected $fillable = [
        'user_id', 'last_name', 'first_name', 'photo', 'photo_url',
        'company','web_site','office_phone','fax','description', 'external_id','full_name','logo_path', 'license', 'is_verified'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }



}
