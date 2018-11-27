<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'description'];

    /**
     * Retrieve the posts that belong to the tag.
     *
     * @return Illuminate\Database\Eloquent\Relations\Relation
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}
