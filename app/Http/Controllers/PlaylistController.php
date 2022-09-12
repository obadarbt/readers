<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Playlist;
use App\File;
use App\Channel;
class PlaylistController extends Controller
{
    public function create_playlist(Request $req)
        {
            $myArray = explode(',',$req->files_id);
            $count_files=count($myArray); 
                        $playlist=new Playlist();
                        $playlist->Title=$req->name;
                        $detect_name=Playlist::where('Title',$req->name)->get();
                        if(!($detect_name->isEmpty()))
                        {
                            return 'You already have the same name';
                        }
                        $playlist->file_id=$req->files_id;
                        $playlist->user_id=$req->user_id;
                        $decode=base64_decode($req->image);
                        $image_name=date('Y-m-d').uniqid().".".'jpeg';
                        $playlist->image=$image_name;
                        file_put_contents('upload/'.$image_name,$decode);
                        $playlist->number=$count_files;
                        $playlist->save();
                        return 'done';
        }
     public function return_all_playlist(Request $req)
     {
        $playlist=Playlist::where('playlists.user_id',$req->user_id)->join('channels','playlists.user_id','=','channels.id')->select('playlists.id','playlists.Title','playlists.image','playlists.number','playlists.file_id','channels.Name as channel_name')->get();
        return $playlist;
     }
}
           