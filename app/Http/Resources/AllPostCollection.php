<?php

namespace App\Http\Resources;

use App\Models\Like;
use App\Models\User;
use App\Models\Reply;
use App\Models\Comment;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AllPostCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        return $this->collection->map(function ($post) {

            return [
                'id' => $post->id,
                'text' => $post->text,
                'video' => url('/') . $post->video,
                'created_at' => $post->created_at,
                'comments' => Comment::where('post_id',$post->id)->orderBy('updated_at','desc')->get()->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'text' => $comment->text,
                        'time'=> $comment->updated_at->format('m-d-Y H:i:s'),
                        'user' => User::where('id', $comment->user_id)->get()->map(function($user) {
                            return [
                                'id' => $user->id,
                                'name' => $user->name,
                                'image' => url('/') . $user->image
                                ];
                            },
                        ),
                        'replies'=>Reply::where('comment_id',$comment->id)->get()->map(function ($reply) { 
                            return [
                                'id'=> $reply->id,
                                'text'=> $reply->text,
                                'time'=> $reply->updated_at,
                                'user'=> User::where('id', $reply->user_id)->get()->map(function ($user) {
                                    return [
                                        'id'=> $user->id,
                                        'name'=> $user->name,
                                        'image'=> url('/') . $user->image
                                    ];
                                }),
                            ];
                        }),
                    ];
                }),
                'likes' => Like::where('post_id', $post->id)->get()->map(function ($like) {
                    return [
                        'userId' => $like->user_id,
                    ];
                }),
                'favorites' => Favorite::where('post_id', $post->id)->get()->map(function ($favorite) {
                    return [
                        'userId' => $favorite->user_id,
                    ];
                }),
                'user' => User::where('id', $post->user_id)->get()->map(function ($user){
                    return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'image' => url('/') . $user->image
                    ];
                }),
            ];
        });
    }
}
