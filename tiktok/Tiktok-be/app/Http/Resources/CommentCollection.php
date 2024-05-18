<?php

namespace App\Http\Resources;

use App\Models\Reply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        return $this->collection->map(function ($comment) {
            
            return [
                'id' => $comment->id,
                'text' => $comment->text,
                'time'=> $comment->updated_at,
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
        });
    }
}
