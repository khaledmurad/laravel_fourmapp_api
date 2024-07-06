<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    public  function __construct(){
        $this->middleware('auth:sanctum');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Post $post)
    {
        $p = Post::findOrFail($post->id);
        $comments = Comment::where('post_id',$post->id)->with(['user','post'])->latest()->get();

        return $comments;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Post $post,CommentRequest $request)
    {
        $post->comments()->create([
            ...$request->validated(),
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'message' => 'Comment added successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post,Comment $comment)
    {
        $p = Post::findOrFail($post->id);
        $com = $p->comments()->with('user')->where('id',$comment->id)->get();
        return $com;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Post  $post, Comment $comment,CommentRequest $request )
    {
        $comment->update([
            ...$request->validated()
        ]);

        return response()->json([
            'message' => 'updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, Comment $comment)
    {
        $comment->delete();

        return response()->json([
            'message' => 'comment deleted successfully'
        ]);
    }
}
