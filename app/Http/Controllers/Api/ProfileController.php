<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Http\Resources\AllPostCollection;

class ProfileController extends Controller
{
    public function show($id)
    {
        try {
            $post = Post::where("user_id", $id)->orderBy("created_at","desc")->get();
            $user = User::where("id", $id)->get();
            return response()->json([
                'post' => new AllPostCollection($post),
                'user'=> new UserCollection($user),
            ],200);
        } catch (\Exception $e) {
            return response()->json(['error'=>['message'=>$e->getMessage()]],400);
        }
    }
}
