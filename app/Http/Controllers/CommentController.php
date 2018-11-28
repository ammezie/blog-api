<?php

namespace App\Http\Controllers;

use App\Post;
use App\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth:api')->except(['index']);
    }

    /**
     * Display a list of comments for the specified post.
     *
     * @param \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function index(Post $post)
    {
        return CommentResource::collection($post->comments()->paginate(25));
    }

    /**
     * Store a newly created comment in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Post $post
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Post $post)
    {
        // validate inputs
        $validatedData = $request->validate([
            'comment' => 'required',
        ]);

        $comment = $post->comments()->create([
            'comment' => $validatedData['comment'],
        ]);

        return new CommentResource($comment);
    }

    /**
     * Update the specified comment in the database
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post, Comment $comment)
    {
        // validate inputs
        $validatedData = $request->validate([
            'comment' => 'required',
        ]);

        $comment->comment = $validatedData['comment'];

        $post->comments()->save($comment);

        return new CommentResource($comment);
    }
}
