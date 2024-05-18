<?php

namespace App\Http\Controllers\Api;

use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class FollowController extends Controller
{
    function store(Request $request) {
        $request->validate(['user_creator'=>'required']);
        $userIdAuth = auth()->user()->id;
        $userCreator = $request->input(('user_creator'));
        try{
            $isFollowed = DB::table('follows')->where('user_id_creator',$userCreator)->where('user_id_follower',$userIdAuth)->get();
            if(count($isFollowed)== 0) {
                $follow = new Follow();
                $follow->user_id_follower = $userIdAuth;
                $follow->user_id_creator = $userCreator;
                $follow->save();
                return response()->json(['follow'=>[
                    'id'=> $follow->id,
                    'idUserCreator'=> $follow->user_id_creator,
                ]],200);
            }
        }catch(\Exception $e){
            return response()->json(["error"=> $e],400);
        }
    }
    function destroy(Request $request) {
        $request->validate(['user_creator'=>'required']);
        $userIdAuth = auth()->user()->id;
        $userCreator = $request->input(('user_creator'));
        try{
            DB::table('follows')->where('user_id_creator',$userCreator)->where('user_id_follower',$userIdAuth)->delete();
            return response()->json(["status"=>"success"],200);
        }catch(\Exception $e){
            return response()->json(["error"=> $e],400);
        }
    }
}
