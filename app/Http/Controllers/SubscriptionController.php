<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Channel;
use App\Subscription;
use App\User;
use App\File;
class SubscriptionController extends Controller
{
    public function sub(Request $req)
    {
    	$channel=Channel::find($req->channel_id);
    	if(is_null($channel))
    	{
    		return response()->json(['messages'=>'Sorry,channel is not found'],200);
    	}
        $user=User::find($req->user_id);
        if(is_null($user))
        {
            return response()->json(['messages'=>'Sorry,user is not found'],200);
        }
    	$sub_detect=Subscription::where('channel_id',$req->channel_id)->where('user_id',$req->user_id)->get();
    	if($sub_detect->isEmpty())
    	   {   
                $channel->Count_sub++;
                $channel->save();
                $sub=new Subscription;
                $sub->channel_id=$req->channel_id;
                $sub->user_id=$req->user_id;
                $sub->save();
                return response()->json(['messages'=>'Subscribed'],200);
    	   }
         $channel->Count_sub--;
         $channel->save();
         $sub_detect[0]->delete();
         return response()->json(['messages'=>'Subscription canceled'],200);
    }

    public function getchannels(Request $req)
    {
        $sub=Subscription::where('subscriptions.user_id',$req->id)->join('channels','subscriptions.channel_id','=','channels.id')->select('channels.Name','channels.Personal_image','channels.id')->get(); 
        if($sub->isEmpty())
            {
                return response()->json(['messages'=>'There are no channels yet'],200);
            }
        return response()->json($sub);
    }
    public function get_info_of_channels(Request $req)
    {
        $subscriptions=Subscription::where('user_id',$req->id)->select('channel_id')->get();
        $files=File::whereIN('channel_id',$subscriptions)->join('channels','files.channel_id','=','channels.id')->select('files.Title','files.Count_view','files.created_at','files.id','channels.Personal_image','channels.Name','files.image','files.PDF as url_file','channels.id as id_channel')->get();
        return $files;
    }
}
