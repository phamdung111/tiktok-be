<?php

namespace App\Http\Controllers\Api;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class FavoriteController extends Controller
{
    function store (Request $request){
        $request->validate(['post_id'=>'required']);
        $userIdAuth = auth()->user()->id;
        $postId = $request->input(('post_id'));
        try{
            $isFavorite = DB::table('favorites')->where('user_id',$userIdAuth)->where('post_id',$postId)->get();
            if(count($isFavorite)== 0) {
                $favorite = new Favorite();
                $favorite->user_id = $userIdAuth;
                $favorite->post_id = $postId;
                $favorite->save();
                 return response()->json(["status"=>'success'],200);
            }
        }catch(\Exception $e){
            return response()->json(["error"=> $e],400);
        }
    }
    function delete(Request $request) {
        $request->validate(['post_id'=>'required']);
        $userIdAuth = auth()->user()->id;
        $postId = $request->input(('post_id'));
        try{
            DB::table("favorites")->where("user_id", $userIdAuth)->where('post_id',$postId)->delete();
            return response()->json(["status"=>"success"],200);
        }catch(\Exception $e){
            return response()->json(["error"=> $e],400);
        }
    }
}
