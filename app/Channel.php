<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    public function files()
    {
    	return $this->hasMany('App\File','channel_id');
    }
}
