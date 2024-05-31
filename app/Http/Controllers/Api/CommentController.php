<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentCollection;

class CommentController extends Controller
{
    public function store (Request $request){
        $request->validate(['text'=>['required'],'post_id'=>['required']]);
        try{
    
            $comment = new Comment;
            $comment->post_id = $request->input('post_id');
            $comment->user_id = auth()->user()->id;
            $comment->text = $request->input('text');
            
            $comment->save();
            $newComment = Comment::where('id', $comment->id)->get();
            return response()->json(new CommentCollection($newComment),200);

        }catch(\Exception $e){
            return response()->json(['error'=> $e],400);
        }
    }

    public function destroy($id){
        try{
            DB::table('comments')->where('id',$id)->delete();
            DB::table('replies')->where('comment_id',$id)->delete();
            return response()->json(['status'=> 'success'],200);
        }
        catch(\Exception $e){
            return response()->json(['error'=> $e],400);
        }
    }
}
