<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
 	public function replies()
 	{
 		return $this->hasMany('App\Reply','comment_id');
 	}
}
