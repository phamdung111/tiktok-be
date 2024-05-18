<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Services\FileService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\AllPostsCollection;

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
            $post = (new FileService)->addVideo($post, $request);

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
            return response()->json(new AllPostsCollection($post), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getAllPosts(){
        {
        try {
            $posts = DB::table('posts')->orderBy('created_at', 'desc')->get();
            return response()->json(new AllPostsCollection($posts), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
            }
        }
    }

    public function getAllPostsByUser($user_id){
        try {
            $posts = DB::table('posts')->where('user_id',$user_id)->orderBy('updated_at','desc')->get();
            return response()->json(new AllPostsCollection($posts), 200);
        } catch (\Exception $e) {
            return response()->json(['error'=> $e->getMessage()], 400);
        }
    }
}
