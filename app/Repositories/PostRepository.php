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

    /**
     * Get ip list with users
     * @return array
     */
    public function getIpList()
    {
        return DB::select('SELECT author_ip, string_agg(DISTINCT(users.login), \',\') as author_logins
            FROM post main
            INNER JOIN users on users.id = main.user_id
            GROUP BY author_ip
            HAVING count(user_id) >= 1'
        );
    }

    /**
     * @param $limit
     * @return mixed
     */
    public function findPostByLimitWithRating($limit = 10)
    {
        return DB::select('Select post.id, post.title, post.content, 
            to_char(AVG (rating.rating), \'9.9\') AS rating  from post 
            LEFT JOIN rating on rating.post_id = post.id GROUP BY post.id order by rating DESC LIMIT :limit',
            ['limit' => $limit]);
    }
}
