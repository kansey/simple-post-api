<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

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
     * @return mixed
     */
    public function getIp()
    {
        return $this->getCacheIp();
    }

    /**
     * @return mixed
     */
    private function getCacheIp()
    {
        return $value =Cache::remember(QueryCacheService::IP_KEY, QueryCacheService::TTL, function() {
            return DB::select('SELECT author_ip, string_agg(DISTINCT(users.login), \',\') as author_logins
                FROM post main
                INNER JOIN users on users.id = main.user_id
                GROUP BY author_ip
                HAVING count(user_id) > 1'
            );
        });
    }
}
