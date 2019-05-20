<?php

namespace App\Services;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\PostRequest;
use App\Http\Requests\RatingRequest;
use App\Http\Response\ResponseCode;
use App\Post;
use App\Rating;
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
     * @var QueryCacheService  $queryCache
     */
    protected $queryCache;

    /**
     * @var RatingRequest $ratingRequest
     */
    protected $ratingRequest;

    protected $rating;

    /**
     * PostService constructor.
     * @param Post $post
     * @param PostRequest $postRequest
     * @param LoginRequest $loginRequest
     * @param RatingRequest $ratingRequest
     * @param User $user
     * @param ResponseCode $code
     * @param QueryCacheService $cacheService
     * @param Rating $rating
     */
    public function __construct(
        Post $post,
        PostRequest $postRequest,
        LoginRequest $loginRequest,
        RatingRequest $ratingRequest,
        User $user,
        ResponseCode $code,
        QueryCacheService $cacheService,
        Rating $rating
    ) {
        $this->post = $post;
        $this->postRequest = $postRequest;
        $this->loginRequest = $loginRequest;
        $this->ratingRequest = $ratingRequest;
        $this->user = $user;
        $this->code = $code;
        $this->queryCache = $cacheService;
        $this->rating = $rating;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function create(Request $request): array
    {
        $validatePost = $this->postRequest->validate($request);
        $validateLogin = $this->loginRequest->validate($request);

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

    /**
     * @return array
     */
    public function getIpList(): array
    {
        $query = $this->queryCache->getIp();

        return is_array($query) ? [
            $query,
            $this->code->ok
        ] : [
            PostRequest::EMPTY_DATA_MESSAGE,
            $this->code->unprocessableEntity
        ];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function rating(Request $request): array
    {
        /**
         * @var Validator $validate
         */
        $validate = $this->ratingRequest->validate($request);

        if ($validate->fails()) {
            return [
                $validate->errors()->all(),
                $this->code->unprocessableEntity
            ];
        }

        try {
            $this->rating->saveRating($request);
        } catch (\Throwable $e) {
            return [
                RatingRequest::MESSAGE,
                $this->code->unprocessableEntity
            ];
        }

        $rating = $this->rating->getPostRating($request->post_id);

        return isset($rating) && isset($rating->rating) ? [
            ['average' => $rating->rating],
            $this->code->ok
        ] : [
            RatingRequest::MESSAGE,
            $this->code->unprocessableEntity
        ];
    }
}
