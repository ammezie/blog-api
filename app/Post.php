<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'title', 'slug', 'content', 'status'
    ];

    /**
     * Get the user that created this post.
     *
     * @return Illuminate\Database\Eloquent\Relations\Relation
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retrieve all the comments for this post.
     *
     * @return Illuminate\Database\Eloquent\Relations\Relation
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Retrieve the tags that belong to the post.
     *
     * @return Illuminate\Database\Eloquent\Relations\Relation
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
