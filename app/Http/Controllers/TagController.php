<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Resources\TagResource;

class TagController extends Controller
{
    /**
     * Display a list of tags.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TagResource::collection(Tag::paginate(25));
    }

    /**
     * Store a newly created tag in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate inputs
        $validatedData = $request->validate([
            'name' => 'required|unique:tags',
            'description' => 'required',
        ]);

        // create tag
        $tag = Tag::create([
            'name' => $validatedData['name'],
            'slug' => str_slug($validatedData['name']),
            'description' => $validatedData['description'],
        ]);

        return new TagResource($tag);
    }

    /**
     * Display the specified tag.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        return new TagResource($tag);
    }

    /**
     * Update the specified tag in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        $tag->update($request->only(['name', 'description']));

        return new TagResource($tag);
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
