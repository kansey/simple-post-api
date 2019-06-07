<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Repositories\PostRepository;

/**
 * Class QueryCacheService
 * @package App\Services
 */
class QueryCacheService
{
    /**
     * Time life element in cache
     */
    const TTL = 180;

    /**
     * Key for ip in cache
     */
    const IP_KEY = 'ipList';

    /**
     * @var PostRepository $postRepository
     */
    protected $postRepository;

    /**
     * QueryCacheService constructor.
     * @param PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $value = Cache::remember(QueryCacheService::IP_KEY, QueryCacheService::TTL, function() {
            return $this->postRepository->getIpList();
        });
    }
}
