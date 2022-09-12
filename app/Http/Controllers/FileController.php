<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\File;
use Response;
use Validator;
use App\Channel;
use App\User;
use App\Like;
use App\Dislike;
use App\Subscription;
class FileController extends Controller
{
    public function Upload_file(Request $req)
    {
        $rules=
            [      
                'title'=>'required',
                'description'=>'required',
                'image'=>'required',
                'pdf'=>'required',
            ];
        $validate=Validator::make($req->all(),$rules);
        if($validate->fails())
            {
                return response()->json(['messages'=>$validate->messages()],200);  
            }
        $file=new File();
        $file->Title=$req->title;
        $file->Description=$req->description;
        $decode=base64_decode($req->image);
        $image_name=date('Y-m-d').uniqid().".".'jpeg';
        $file->image=$image_name;
        file_put_contents('upload/'.$image_name,$decode);
        $suffix=$req->suff;
        $decode=base64_decode($req->pdf);
        $pdf_name=date('Y-m-d').uniqid().".".$suffix;
        $file->PDF=$pdf_name;
        file_put_contents(('upload/').$pdf_name,$decode);
        $file->channel_id=$req->channel_id;
        $file->user_id=$req->user_id;
        $file->save();
        return response()->json(['messages'=>'File uploaded'],200); 
    }
    public function all_files(Request $req)
    {
        $file=File::where('user_id',$req->id)->select('id','Title','created_at','Description','image','PDF','Count_view')->get();
        if($file->isempty())
        {
            return response()->json([['check'=>0]],200); 
        }
        return response()->json($file);
    }
    public function delete(Request $req)
    {
        $file=File::find($req->id);
        unlink(public_path('upload/').$file->PDF);
        unlink(public_path('upload/').$file->image);
        $file->delete();
        return 'success';
    }
    public function edit_file(Request $req)
    {
        $file=File::find($req->id);
        if(isset($req->title))
        {
            $file->Title=$req->title;
        }
        if(isset($req->description))
        {
            $file->Description=$req->description;
        }
        if(isset($req->image))
        {
            unlink(public_path('upload/').$file->image);
            $decode=base64_decode($req->image);
            $image_name=date('Y-m-d').uniqid().".".'jpeg';
            $file->image=$image_name;
            file_put_contents('upload/'.$image_name,$decode);
        }
        $file->save();
        return 'done';
    }
    public function getfiles()
    {
        $file=File::join('channels','files.channel_id','=','channels.id')->select('files.id','files.Title','files.created_at','files.Count_view','channels.Personal_image','channels.Name','files.image','files.PDF as url_file','channels.id as id_channel')->inRandomOrder()->take(4)->get();
        return $file;
    }
    public function viewfile(Request $req)
    {
        $file=File::where('files.id',$req->file_id)->join('channels','files.channel_id','=','channels.id')->select('files.PDF','files.Title','files.created_at','files.Count_view','channels.Personal_image','channels.Name','channels.Count_sub','files.like','files.dislike','files.Description','channels.id as channel_id')->first();
        $like=Like::where('file_id',$req->file_id)->where('user_id',$req->user_id)->get();
        $dislike=Dislike::where('file_id',$req->file_id)->where('user_id',$req->user_id)->get();
        if(!($like->isEmpty()))
        {
            $check=1;
        }
        elseif (!($dislike->isEmpty()))
        {
            $check=2;
        }
        else
        {
            $check=0;
        }
        $subscription=Subscription::where('user_id',$req->user_id)->where('channel_id',$req->user_id)->get();
        if($subscription->isempty())
        {
            $check_sub=0;
        }
        else
        {
            $check_sub=1;
        }
        return response()->json([$file,$check,$check_sub],200);
    }

    public function suggestedfiles(Request $req)
    {   
        $get_id=File::where('id','=',$req->id)->first();       
        $file1= File::where('files.user_id',$get_id->user_id)->whereNotIn('files.id',[$req->id])->join('channels','files.channel_id','=','channels.id')->select('files.image','files.Title','channels.Name','files.created_at','files.Count_view','files.id','files.PDF as url_file')->inRandomOrder()->take(10)->get();
        if(count($file1)<10)
        {   
            $count_related=count($file1);
            $count_sub=10-$count_related;
            $file2=File::whereNotIn('files.user_id',[$get_id->user_id])->join('channels','files.channel_id','=','channels.id')->select('files.PDF as url_file','files.id','files.Title','files.created_at','files.Count_view','channels.Personal_image','channels.Name','files.image')->inRandomOrder()->take($count_sub)->get();
            $result=array_merge($file1->toArray(),$file2->toArray());
            return $result;
        }
        return $file1;
    }
    public function search_for_file(Request $req)
    {
        $file=File::where('files.Title','like','%'.$req->key_word.'%')->join('channels','files.channel_id','=','channels.id')->select('files.Title','files.image','channels.Name','files.id as file_id','files.created_at','files.Count_view','files.PDF as url_file')->get();
        return $file;
    }
}
