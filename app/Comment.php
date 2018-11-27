<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['post_id', 'comment'];

    /**
     * Get the post this comment was left on.
     *
     * @return Illuminate\Database\Eloquent\Relations\Relation
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
