<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function store(Post $post,Request $request)
    {
        $p = Post::where('id', $post->id)->first();

        if(!$p){
            return response()->json([
                'message' => 'post not found!'
            ]);
        }

        $unlikePost = Like::where('user_id',auth()->id())->where('post_id',$post->id)->delete();

        if($unlikePost){
            return response()->json([
                'message' => 'Unliked'
            ]);
        }
        $likePost = Like::create([
            'user_id' => auth()->id(),
            'post_id' => $post->id
        ]);
        if($likePost){
            return response()->json([
                'message' => 'Liked'
            ]);
        }
    }

}
