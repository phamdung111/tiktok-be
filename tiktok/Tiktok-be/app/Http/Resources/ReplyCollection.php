<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReplyCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        return $this->collection->map(function ($reply) {
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
        });
    }
}
