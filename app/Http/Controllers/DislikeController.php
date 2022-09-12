<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dislike;
use App\File;
use App\User;
use App\Like;
class DislikeController extends Controller
{
    public function dislike(Request $req)
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
		$dislike_detect=Dislike::where('file_id',$req->file_id)->where('user_id',$req->user_id)->get();
        $like_detect=Like::where('file_id',$req->file_id)->where('user_id',$req->user_id)->get();
        if($like_detect->isEmpty())
        {
    	   if($dislike_detect->isEmpty())
    	           {   
                        $file->dislike++;
                        $file->save();
                        $dislike=new Dislike;
                        $dislike->file_id=$req->file_id;
                        $dislike->user_id=$req->user_id;
                        $dislike->save();
                        return response()->json(['true',$file->like,$file->dislike]);
    	           }
            $file->dislike--;
            $file->save();
            $dislike_detect[0]->delete();
            return response()->json(['false',$file->like,$file->dislike]);
        }
        $like_detect[0]->delete();
        $file->like--;
        $file->dislike++;
        $file->save();
        $dislike=new Dislike;
        $dislike->file_id=$req->file_id;
        $dislike->user_id=$req->user_id;
        $dislike->save();
        return response()->json(['true',$file->like,$file->dislike]);
	}
}
