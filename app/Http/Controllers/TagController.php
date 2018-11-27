<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\TagResource;
use App\Http\Controllers\AuthController;

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
        // validate inputs
        $validatedData = $request->validate([
            'name' => [
                'required',
                Rule::unique('tags')->ignore($tag->id),
            ],
            'description' => 'required',
        ]);

        $tag->update($request->only(['name', 'description']));

        return new TagResource($tag);
    }

    /**
     * Remove the specified tag from the database.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return response()->json(null, 204);
    }
}
