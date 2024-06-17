<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\AllPostCollection;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'video' => 'required|mimes:mp4',
            'text' => 'required'
        ]);

        try {
            $post = new Post;
            $video = $request->file('video');
            $name = $video->hashName();
            Storage::putFileAs('videos', $video, $name);
            $post->video = '/storage/videos/' . $name;
            $post->user_id = auth()->user()->id;
            $post->text = $request->input('text');
            $post->save();

            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $post = Post::findOrFail($id);
            if (!is_null($post->video) && file_exists(public_path() . $post->video)) {
                unlink(public_path() . $post->video);
            }
            $post->delete();

            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
}

    public function getPostById($id){
        try {
            $post = Post::where("id",$id)->get();
            return response()->json(new AllPostCollection($post), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function Posts(Request $request){
        $offset = $request->get('offset');
        $limit = $request->get('limit');
        {
        try {
            $posts = DB::table('posts')
                ->orderBy('created_at', 'desc')
                ->offset($offset)
                ->limit($limit)
                ->get();
            return response()->json(new AllPostCollection($posts), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
            }
        }
    }

    public function getAllPostsByUser($user_id){
        try {
            $posts = DB::table('posts')->where('user_id',$user_id)->orderBy('updated_at','desc')->get();
            return response()->json(new AllPostCollection($posts), 200);
        } catch (\Exception $e) {
            return response()->json(['error'=> $e->getMessage()], 400);
        }
    }
    public function postByFollowing(Request $request){
        try{
            $offset = $request->get('offset');
            $limit = $request->get('limit');
            $usersFollowing = DB::table('follows')
                ->where('user_id_follower', auth()->user()->id)
                ->select('user_id_creator');

            $posts = DB::table('posts')
                ->joinSub($usersFollowing, 'users_following', function ($join) {
                    $join->on('posts.user_id', '=', 'users_following.user_id_creator');
                })
                ->orderBy('updated_at','desc')
                ->offset($offset)
                ->limit($limit)
                ->get();
            return response()->json(new AllPostCollection($posts),200);
        } catch (\Exception $e) {
            return response()->json(['error'=> $e->getMessage()], 400);
        }
    }
}
