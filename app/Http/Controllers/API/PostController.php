<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\PostRequest;
use App\Services\PostService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;


class PostController extends Controller
{

    protected $postService;

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
        return response()->json($this->postService->create($request));
    }
}
