<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PeopleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        return $this->collection->map(function ($person) {
            return [
                'id'=>$person->id,
                'name'=>$person->name,
                'image'=> url('/') . $person->image,
                'bio'=>$person->bio,
                'videos'=>DB::table('posts')->where('user_id',$person->id)->get()->map(function ($post) {
                    return[
                        'id' => $post->id,
                        'video'=> url('/') . $post->video,
                        'text'=> $post->text
                    ];
                }),
                'following'=>DB::table('follows')->where('user_id_follower',$person->id)->get()->map(function ($follow){
                    return [
                        'id'=>$follow->id,
                        'idUserCreator'=>$follow->user_id_creator,
                    ];
                }),
                'favorite'=>DB::table('posts')->join('favorites', function( $join) {
                    $join->on('posts.id','=','favorites.post_id');
                })->where('favorites.user_id','=',$person->id)
                    ->get()->map(function ($favorite){
                        return[
                            'id'=> $favorite->id,
                            'video' => url('/') . $favorite->video,
                            'text'=>$favorite->text,
                            'postId'=>$favorite->post_id
                        ];
                    }),
                'follower'=>DB::table('follows')->where('user_id_creator',$person->id)->count(),
                'liked'=>DB::table('posts')->join('likes', function($join){
                    $join->on('posts.id','=','likes.post_id');
                })->where('likes.user_id','=',$person->id)
                    ->get()->map(function ($like){
                        return[
                            'id'=> $like->id,
                            'video' => url('/') . $like->video,
                            'text'=>$like->text,
                            'postId'=>$like->post_id
                        ];
                }),
            ];
        });
    }
}
