<?php

namespace App\Http\Controllers;

use App\Post;
use App\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
}
