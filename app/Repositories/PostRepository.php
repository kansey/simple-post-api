<?php

namespace App\Repositories;

use App\Post;
use Illuminate\Support\Facades\DB;

/**
 * Class PostRepository
 * @package App\Repositories
 */
class PostRepository
{
    /**
     * @var Post $post
     */
    protected $post;

    /**
     * PostRepository constructor.
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function firstOrCreate($params = array())
    {
        return DB::transaction(function () use ($params) {
            return $this->post->firstOrCreate($params);
        });
    }
}
