<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Channel;
use App\File;
use Response;
use App\Subscription;
class ChannelController extends Controller
{
    public function EditName(Request $req)
    {
    	$channel=Channel::find($req->id);
    	if(is_null($channel))
    		{
    			return response()->json(['messages'=>'Channel is not Found'],200);
    		}
    	$channel->Name=$req->name;
    	$channel->save();
    	return response()->json(['messages'=>'The name has been modified'],200); 
    }
    public function EditDescription(Request $req)
    {
    	$channel=Channel::find($req->id);
    	if(is_null($channel))
    		{
    			return response()->json(['messages'=>'Channel is not Found'],200);
    		}
    	$channel->Description=$req->description;
    	$channel->save();
    	return response()->json(['messages'=>'Description has been modified'],200); 
    }
    public function EditPersonal(Request $req)
    {
    	$channel=Channel::find($req->id);
    	if(is_null($channel))
    		{
    			return response()->json(['messages'=>'Channel is not Found'],200);
    		}
        if($channel->Personal_image!='personal_empty.jpg')
        {
            unlink(public_path('upload/').$channel->Personal_image);
        }
    	$decode=base64_decode($req->image);
    	$image_name=date('Y-m-d').uniqid().".".'jpeg';
        file_put_contents('upload/'.$image_name,$decode);
        $channel->Personal_image=$image_name;
        $channel->save(); 
        return 'image has been modified' ;
    }
    public function Editbackground(Request $req)
    {
    	$channel=Channel::find($req->id);
        if(is_null($channel))
            {
                return response()->json(['messages'=>'Channel is not Found'],200);
            }
        if($channel->Background_image!='background_empty.png')
        {
            unlink(public_path('upload/').$channel->Background_image);
        }
        $decode=base64_decode($req->image);
        $image_name=date('Y-m-d').uniqid().".".'jpeg';
        file_put_contents('upload/'.$image_name,$decode);
        $channel->background_image=$image_name;
        $channel->save();
        return 'image has been modified'; 
    }
     public function search_for_channel(Request $req)
    {
        $channel=Channel::where('Name','like','%'.$req->key_word.'%')->select('id','Personal_image','Count_sub','Name')->withCount('files')->get();
        return $channel;
    }
}