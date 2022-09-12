<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Reply;
use App\User;
use App\Channel;
class ReplyComment extends Controller
{
    public function add_reply(Request $req)
    {
    	$reply=new Reply;
    	$reply->text=$req->text;
    	$reply->user_id=$req->user_id;
    	$reply->comment_id=$req->comment_id;
    	$reply->save();
        return response()->json(['messages'=>'You add reply','status'=>'true']);
    }
    public function return_all_replies(Request $req)
    {
    	$replies=Reply::where('replies.comment_id',$req->comment_id)->join('users','replies.user_id','=','users.id')->join('channels','users.id','=','channels.id')->select('users.name','channels.Personal_image','replies.text','replies.created_at')->get();
    	return $replies;
    }
}
