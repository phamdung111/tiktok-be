<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\AllPostCollection;

class SearchController extends Controller
{
    public function search($content) {
        try{
            $searchAccount = DB::table('users')
            ->where('name','like','%' . $content . '%')
            ->join('follows','users.id','=','follows.user_id_creator')
            ->select(
                'users.id',
                'users.name',
                'users.bio',
                DB::raw("CONCAT('" . url('/') . "', users.image) as image"),
                DB::raw("COUNT(follows.user_id_creator) as follower")
            )
            ->groupBy('users.id')
            ->get();
            $searchPosts = DB::table('posts')->where('posts.text','like','%'.$content.'%')->get();
            return response()->json(['users'=>$searchAccount, 'posts'=> new AllPostCollection($searchPosts)],200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

    }
}
