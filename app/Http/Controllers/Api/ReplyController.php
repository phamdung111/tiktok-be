<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ReplyCollection;
use App\Models\Reply;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReplyController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'text'=>['required'],
            'comment_id'=>['required'],
        ]);
        try {
        $reply = new Reply();
        $reply->user_id = auth('')->user()->id;
        $reply->comment_id = $request->comment_id;
        $reply->text = $request->text;

        $reply->save();
        $newReply = Reply::where('id',$reply->id)->get();
        return response()->json(new ReplyCollection($newReply),200);
        } catch (\Exception $e) {
            return response()->json(['error'=> $e],400);
        }
    }
    public function destroy($id){
        try {
            DB::table('replies')->where('id',$id)->delete();
            return response()->json(['status'=> 'success'],200);
        } catch (\Exception $e) {
            return response()->json(['error'=> $e],400);
        }
    }
}
