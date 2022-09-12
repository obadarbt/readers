<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\View_later;
use App\Channel;
use App\File;
class ViewlaterController extends Controller
{
    public function view_later(Request $req)
    {
    	$view__later_detect=View_later::where('file_id',$req->file_id)->where('user_id',$req->user_id)->get();
    	if($view__later_detect->isEmpty())
    	{
    		$view_later=new View_later;
    		$view_later->file_id=$req->file_id;
    		$view_later->user_id=$req->user_id;
    		$view_later->save();
    		return response()->json(['messages'=>'The file has been added to watch later']); 
    	}
    	else
    	{
    		return response()->json(['messages'=>'You already added it']); 
    	}
    }
    public function delete_view_later(Request $req)
    {
    	$view__later=View_later::where('file_id',$req->file_id)->where('user_id',$req->user_id)->get();
    	if($view__later->isEmpty())
    	{
    		return response()->json(['messages'=>'Sorry , the file is not exist']); 
    	}
    	else
    	{
    		$view__later[0]->delete();
    		return response()->json(['messages'=>'Done']);
    	}
    }
    public function all_view_later(Request $req)
    {
        $view_later=View_later::where('view_laters.user_id',$req->user_id)->join('files','view_laters.file_id','=','files.id')->join('channels','files.channel_id','=','channels.id')->select('files.Title','files.image','files.id','channels.Name','files.created_at','files.Count_view')->get();
        return $view_later;
    }
}
