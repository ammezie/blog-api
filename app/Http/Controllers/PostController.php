<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\PostResource;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    /**
     * Display a list of posts.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PostResource::collection(Post::latest()->paginate(25));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate inputs
        $validatedData = $request->validate([
            'title' => 'required|unique:posts',
            'content' => 'required',
            'status' => 'required',
            'tags' => 'required',
        ]);

        // create post
        $post = Post::create([
            'user_id' => $request->user()->id,
            'title' => $validatedData['title'],
            'slug' => str_slug($validatedData['title']),
            'content' => $validatedData['content'],
            'status' => !!$validatedData['status'],
        ]);

        // attach tags to the post
        $post->tags()->attach($validatedData['tags']);

        return new PostResource($post);
    }

    /**
     * Display the specified post.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Update the specified post in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        // validate inputs
        $validatedData = $request->validate([
            'title' => [
                'required',
                Rule::unique('posts')->ignore($post->id)
            ],
            'content' => 'required',
            'status' => 'required',
            'tags' => 'required',
        ]);

        // update post
        $post->update([
            'title' => $validatedData['title'],
            'slug' => str_slug($validatedData['title']),
            'content' => $validatedData['content'],
            'status' => !!$validatedData['status'],
        ]);

        // attach tags to the post
        $post->tags()->sync($validatedData['tags']);

        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
