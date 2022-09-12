<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Channel;
use Validator;
use Response;
class UserController extends Controller
{

    public function register(Request $req)
    {
    $rules=
    	[
    		'name'=>'required|regex:/^[a-zA-Z]+$/u',
    		'email'=>'required|email|unique:users,email',
    		'password'=>'required|min:5',
        ];
    $validate=Validator::make($req->all(),$rules);
    if($validate->fails())
    {
    	return response()->json(['messages'=>$validate->messages(),'check'=>0],200);  
    }
        $channel=new Channel;
    	$user=new User;
    	$user->name=$req->name;
    	$user->email=$req->email;
    	$user->password=$req->password;
    	$user->save();
        $channel->User_id=$user->id;
        $channel->Name=$user->name;
        $channel->Personal_image='personal_empty.jpg';
        $channel->Background_image='background_empty.png';
        $channel->save();
    	return response()->json(['messages'=>'The account has been created','user_id'=>$user->id,'check'=>1,'create_date'=>$user->created_at->format('m/d/Y'),'personal_image'=>$channel->Personal_image,'background_image'=>$channel->Background_image],200);
    }

    public function login(Request $req)
    {
    	$rules=
    	[
    		'email'=>'required',
    		'password'=>'required'
        ];
        $validate=Validator::make($req->all(),$rules);
        if($validate->fails())
    		{
    			return response()->json(['messages'=>$validate->messages(),'check'=>0],200);  
    		}
    $user = User::where('email', '=',$req->email)->first();
    if(is_null($user))
    	{
    		return response()->json(['messages'=>'user not exist','check'=>0],200);   
   	    }
        $channel=Channel::find($user->id);
		if($req->password==$user->password)
		{
			return response()->json(['messages'=>'Your are Welcome','id'=>$user->id,'userName'=>$user->name,'create_date'=>$user->created_at->format('m/d/Y'),'description'=>$channel->Description,'personal_image'=>$channel->Personal_image,'background_image'=>$channel->Background_image,'check'=>1],200); 
		}	
    		return response()->json(['messages'=>'Password is not correct','check'=>0],200); 
    }
    public function user_info(Request $req)
    {
        $user = User::find($req->id);
        if(is_null($user))
        {
            return response()->json(['messages'=>'user not exist','check'=>0],200);   
        }
        $channel=Channel::find($user->id);
        return response()->json(['messages'=>'Your are Welcome','id'=>$user->id,'Username'=>$user->name,'Create_date'=>$user->created_at->format('m/d/Y'),'Description'=>$channel->Description,'Personal_image'=>$channel->Personal_image,'Background_image'=>$channel->Background_image,'Channel_name'=>$channel->Name,'User_email'=>$user->email,'User_password'=>$user->password,'Count_sub'=>$channel->Count_sub],200);
    }
}
