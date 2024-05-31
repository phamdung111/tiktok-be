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
            $result = DB::table('posts')->where('posts.text','LIKE','%'.$content.'%')->get();
            return response()->json(new AllPostCollection($result),200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

    }
}
