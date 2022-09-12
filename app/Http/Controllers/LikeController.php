<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Like;
use App\File;
use App\User;
use App\Dislike;
class LikeController extends Controller
{
	public function like(Request $req)
	{
		$file=File::find($req->file_id);
    	if(is_null($file))
    	{
    		return response()->json(['messages'=>'Sorry,file is not found'],200);
    	}
    	$user=User::find($req->user_id);
        if(is_null($user))
        {
            return response()->json(['messages'=>'Sorry,user is not found'],200);
        }
		$like_detect=Like::where('file_id',$req->file_id)->where('user_id',$req->user_id)->get();
        $dislike_detect=Dislike::where('file_id',$req->file_id)->where('user_id',$req->user_id)->get();
        if($dislike_detect->isEmpty())
        {
    	   if($like_detect->isEmpty())
    	           {   
                        $file->like++;
                        $file->save();
                        $like=new Like;
                        $like->file_id=$req->file_id;
                        $like->user_id=$req->user_id;
                        $like->save();
                        return response()->json(['true',$file->like,$file->dislike]);
    	           }
            $file->like--;
            $file->save();
            $like_detect[0]->delete();
            return response()->json(['false',$file->like,$file->dislike]);
        }
        $dislike_detect[0]->delete();
        $file->dislike--;
        $file->like++;
        $file->save();
        $like=new Like;
        $like->file_id=$req->file_id;
        $like->user_id=$req->user_id;
        $like->save();
        return response()->json(['true',$file->like,$file->dislike]);;

	}
    
}
