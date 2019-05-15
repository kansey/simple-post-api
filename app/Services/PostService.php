<?php

namespace App\Services;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\PostRequest;
use App\Post;
use Illuminate\Http\Request;

/**
 * Class PostService
 * @package App\Services
 */
class PostService
{
    /**
     * @var Post $post
     */
    protected  $post;

    /**
     * @var PostRequest $postRequest
     */
    protected $postRequest;

    /**
     * @var LoginRequest $loginRequest
     */
    protected $loginRequest;

    /**
     * PostService constructor.
     * @param Post $post
     * @param PostRequest $postRequest
     * @param LoginRequest $loginRequest
     */
    public function __construct(Post $post, PostRequest $postRequest, LoginRequest $loginRequest)
    {
        $this->post = $post;
        $this->postRequest = $postRequest;
        $this->loginRequest = $loginRequest;
    }

    /**
     * @param Request $request
     */
    public function create(Request $request)
    {
        $validatePost = $this->postRequest->validatePost($request);
        $validateLogin = $this->loginRequest->validateLogin($request);

        if ($validatePost->fails() || $validateLogin->fails()) {
            return array_merge($validatePost->errors()->all(), $validateLogin->errors()->all());
        }
    }
}
