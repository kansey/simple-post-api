<?php

namespace App\Services;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\PostRequest;
use App\Http\Response\ResponseCode;
use App\Post;
use App\User;
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
     * @var User $user
     */
    protected $user;

    /**
     * @var ResponseCode $code
     */
    protected $code;

    /**
     * PostService constructor.
     * @param Post $post
     * @param PostRequest $postRequest
     * @param LoginRequest $loginRequest
     * @param User $user
     * @param ResponseCode $code
     */
    public function __construct(
        Post $post,
        PostRequest $postRequest,
        LoginRequest $loginRequest,
        User $user,
        ResponseCode $code
    ) {
        $this->post = $post;
        $this->postRequest = $postRequest;
        $this->loginRequest = $loginRequest;
        $this->user = $user;
        $this->code = $code;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function create(Request $request)
    {
        $validatePost = $this->postRequest->validatePost($request);
        $validateLogin = $this->loginRequest->validateLogin($request);

        if ($validatePost->fails() || $validateLogin->fails()) {
            return [
                array_merge($validatePost->errors()->all(), $validateLogin->errors()->all()),
                $this->code->unprocessableEntity
            ];
        }

        $user = $this->user->firstOrCreate(['login' => $request->login]);

       return [
           $this->post->firstOrCreate([
               'title' => $request->title,
               'content' => $request->content,
               'user_id' => $user->id,
               'author_ip' => $request->author_ip
           ]),
           $this->code->ok
       ];
    }
}
