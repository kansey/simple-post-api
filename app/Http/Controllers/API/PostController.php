<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\PostRequest;
use App\Services\PostService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

/**
 * Class PostController
 * @package App\Http\Controllers\API
 */
class PostController extends Controller
{
    /**
     * @var PostService $postService
     */
    protected $postService;

    /**
     * PostController constructor.
     * @param PostService $postService
     */
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        list($response, $code) = $this->postService->create($request);

        return response()->json($response, $code);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ip(Request $request)
    {
        list($response, $code) = $this->postService->getIpList($request);

        return response()->json($response, $code);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function rating(Request $request)
    {
        list($response, $code) = $this->postService->rating($request);

        return response()->json($response, $code);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function posts(Request $request)
    {
        list($response, $code) = $this->postService->posts($request);

        return response()->json($response, $code);
    }
}
