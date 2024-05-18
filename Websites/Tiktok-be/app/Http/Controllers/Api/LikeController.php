<?php

namespace App\Http\Controllers\Api;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LikeController extends Controller
{
    public function store(Request $request){
        $request->validate(['post_id' => 'required']);
        $userId = auth()->user()->id;
        $postId = $request->input("post_id");
        try
        {
            $likeExits = DB::table("likes")->where("user_id", $userId)->where("post_id",$postId)->get();
            if(count($likeExits) == 0){
                $like = new Like;
                $like->user_id = $userId;
                $like->post_id = $postId;
                $like->save();
                return response()->json(["status"=>'success'],200);
            }
        }catch(\Exception $e){
            return response()->json(["error"=> $e],400);
        }
    }

    public function destroy(Request $request){
        $request->validate(["post_id"=> "required"]);
        $userIdAuth = auth()->user()->id;
        $postId = $request->input("post_id");
        try{
            DB::table("likes")->where("user_id", $userIdAuth)->where('post_id',$postId)->delete();
            return response()->json(["status"=>"success"],200);
        }catch(\Exception $e){
            return response()->json(["error"=> $e],400);
        }
    }
}
