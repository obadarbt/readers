<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\File;
use App\History;
use App\User;
use App\Channel;

class HistoryController extends Controller
{
    public function add_to_history(Request $req)
    {
    	$file=File::where('id',$req->file_id)->first();
    	$file->Count_view++;
    	$file->save();
    	$history=new History;
    	$history->user_id=$req->user_id;
    	$history->file_id=$req->file_id;
    	$history->save();
    }
    public function return_history16(Request $req)
    {
    	$history=History::where('histories.user_id',$req->user_id)->join('files','histories.file_id','=','files.id')->join('channels','files.channel_id','=','channels.id')->select('files.Title','files.image','channels.Name','files.id as file_id','files.PDF as url_file','histories.id as history_id')->take(16)->latest('histories.id')->get();
    	return $history;
    }
    public function return_history_all(Request $req)
    {
        $history=History::where('histories.user_id',$req->user_id)->join('files','histories.file_id','=','files.id')->join('channels','files.channel_id','=','channels.id')->select('files.Title','files.image','channels.Name','files.id as file_id','files.created_at','files.Count_view','histories.id as history_id','files.PDF as url_file')->latest('histories.id')->get();
        return $history;
    }
    public function delete_history(Request $req)
    {
        $history=History::where('id',$req->id)->get();
        if($history->isEmpty())
        {
            return response()->json(['messages'=>'Sorry , the file is not exist']); 
        }
        else
        {
            $history[0]->delete();
            return response()->json(['messages'=>'Done']);
        }
    }
}
