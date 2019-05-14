<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * Table name
     * @var string $table
     */
    protected $table = 'post';

    /**
     * Off should be timestamped
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['title', 'content', 'user_id', 'author_ip'];
}
