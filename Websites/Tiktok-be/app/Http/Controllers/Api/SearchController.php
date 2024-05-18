<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\AllPostsCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function search($content) {
        try{
            $result = DB::table('posts')->where('posts.text','LIKE','%'.$content.'%')->get();
            return response()->json(new AllPostsCollection($result),200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

    }
}
