<?php

namespace App\Http\Resources;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UsersCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        return $this->collection->map(function ($user) {
            return [
                'id'=>$user->id,
                'name'=>$user->name,
                'bio'=>$user->bio,
                'email'=> $user->email,
                'image'=> url('/') . $user->image,
                'myPosts'=>DB::table('posts')->where('user_id',$user->id)->get()->map(function ($post) {
                    return[
                        'id' => $post->id,
                        'video'=> url('/') . $post->video,
                        'text'=> $post->text
                    ];
                }),
                'following'=>DB::table('follows')->where('user_id_follower',$user->id)->get()->map(function ($follow){
                    return [
                        'id'=>$follow->id,
                        'idUserCreator'=>$follow->user_id_creator,
                    ];
                }),
                'favorite'=>DB::table('posts')->join('favorites', function( $join) {
                    $join->on('posts.id','=','favorites.post_id');
                })->where('favorites.user_id','=',$user->id)
                    ->get()->map(function ($favorite){
                        return[
                            'id'=> $favorite->id,
                            'video' => url('/') . $favorite->video,
                            'text'=>$favorite->text,
                            'postId'=>$favorite->post_id
                        ];
                    }),
                'follower'=>DB::table('follows')->where('user_id_creator',$user->id)->count(),
                'liked'=>DB::table('posts')->join('likes', function($join){
                    $join->on('posts.id','=','likes.post_id');
                })->where('likes.user_id','=',$user->id)
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