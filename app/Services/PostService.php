<?php

namespace App\Services;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\PostRequest;
use App\Http\Requests\RatingRequest;
use App\Http\Response\ResponseCode;
use App\Rating;
use App\Repositories\PostRepository;
use App\Http\Requests\PostsRequest;
use App\Repositories\RatingRepository;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Http\Request;

/**
 * Class PostService
 * @package App\Services
 */
class PostService
{
    /**
     * @var PostRepository $postRepository
     */
    protected  $postRepository;

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
    protected $userRepository;

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

    /**
     * @var Rating $rating
     */
    protected $ratingRepository;

    protected $postsRequest;

    /**
     * PostService constructor.
     * @param PostRepository $postRepository
     * @param PostRequest $postRequest
     * @param LoginRequest $loginRequest
     * @param RatingRequest $ratingRequest
     * @param UserRepository $userRepository
     * @param ResponseCode $code
     * @param QueryCacheService $cacheService
     * @param RatingRepository $ratingRepository
     * @param PostsRequest $postsRequest
     */
    public function __construct(
        PostRepository $postRepository,
        PostRequest $postRequest,
        LoginRequest $loginRequest,
        RatingRequest $ratingRequest,
        UserRepository $userRepository,
        ResponseCode $code,
        QueryCacheService $cacheService,
        RatingRepository $ratingRepository,
        PostsRequest $postsRequest
    ) {
        $this->postRepository = $postRepository;
        $this->postRequest = $postRequest;
        $this->loginRequest = $loginRequest;
        $this->ratingRequest = $ratingRequest;
        $this->userRepository = $userRepository;
        $this->code = $code;
        $this->queryCache = $cacheService;
        $this->ratingRepository = $ratingRepository;
        $this->postsRequest = $postsRequest;
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

        try {
            $user = $this->userRepository->firstOrCreate(['login' => $request->login]);
            $post = $this->postRepository->firstOrCreate([
                'title' => $request->title,
                'content' => $request->content,
                'user_id' => $user->id,
                'author_ip' => $request->author_ip
            ]);

            return [$post, $this->code->ok];
        }
        catch (\Exception $exception) {
            return [$exception, $this->code->unprocessableEntity];
        }
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
            return [$validate->errors()->all(), $this->code->unprocessableEntity];
        }

        try {
            $this->ratingRepository->saveRating($request);

            $rating = $this->ratingRepository->findRatingByPostId($request);

            return isset($rating) && isset($rating->rating)
            ? [
                ['average' => $rating->rating],
                $this->code->ok
            ] : [
                RatingRequest::MESSAGE,
                $this->code->unprocessableEntity
            ];

        } catch (\Throwable $e) {
            return [RatingRequest::MESSAGE, $this->code->unprocessableEntity];
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    public function posts(Request $request)
    {
        $validate = $this->postsRequest->validate($request);

        if ($validate->fails()) {
            return [$validate->errors()->all(), $this->code->unprocessableEntity];
        }

        try {
            $posts = $this->postRepository->findPostByLimitWithRating($request->post);
            $posts = collect($posts);
            $posts = $posts->map(function ($item, $key) {
                return ['title' => $item->title, 'content' => $item->content];
            });

            return [$posts->toArray(), $this->code->ok];

        } catch (\Throwable $e) {
            return [RatingRequest::MESSAGE, $this->code->unprocessableEntity];
        }
    }
}
