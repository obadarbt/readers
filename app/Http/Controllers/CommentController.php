<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Comment;
use App\User;
use App\Channel;
class CommentController extends Controller
{
    public function add_comment(Request $req)
    {
    	$comment=new Comment;
    	$comment->text=$req->text;
    	$comment->file_id=$req->file_id;
    	$comment->user_id=$req->user_id;
    	$comment->save();
        return response()->json(['messages'=>'You add comment','status'=>'true','id'=>$comment->id,'date'=>$comment->created_at]);
    }
    public function return_all_comments(Request $req)
    {
    	$comments=Comment::where('comments.file_id',$req->file_id)->join('users','comments.user_id','=','users.id')->join('channels','users.id','=','channels.id')->select('users.name','channels.Personal_image','comments.text','comments.id','comments.created_at')->withCount('replies')->get();
    	return $comments;
    }
    public function number_of_comments_for_file(Request $req)
    {
        $comments=Comment::where('file_id',$req->file_id)->get();
        return count($comments);
    }
    public function return_comment(Request $req)
    {
        $comment=Comment::where('comments.id',$req->id)->join('users','comments.user_id','=','users.id')->join('channels','users.id','=','channels.id')->select('users.name','channels.Personal_image','comments.text','comments.id','comments.created_at')->withCount('replies')->get();
        return $comment;
    }
}
