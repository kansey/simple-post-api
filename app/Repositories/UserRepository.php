<?php

namespace App\Repositories;

use App\User;
use Illuminate\Support\Facades\DB;

/**
 * Class UserRepository
 * @package App\Repositories
 */
class UserRepository
{
    /**
     * @var User $user
     */
    protected $user;

    /**
     * UserRepository constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param array $filter
     * @param bool $withTrashed
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function find(array $filter = [], $withTrashed = false)
    {
        $query = $this->user->query();

        if (array_key_exists('login', $filter)) {
            $query->where('login', $filter['login']);
        }

        if ($withTrashed) {
            $query->withTrashed();
        }

        return $query->get();
    }


    /**
     * @param array $params
     * @return mixed
     */
    public function firstOrCreate($params = array())
    {
        return DB::transaction(function () use ($params) {
            return $this->user->firstOrCreate($params);
        });
    }
}
