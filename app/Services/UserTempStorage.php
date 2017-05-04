<?php

namespace App\Services;

use App\User;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Contracts\Cache\Repository;

class UserTempStorage
{

    const USER_CACHE_NAMESPACE = 'users:temp';

    /**
     * Cache Repository instance.
     * 
     * @var Repository
     */
    protected $cache;

    /**
     * User instance
     *
     * @var User
     */
    protected $user;

    public function __construct(Repository $cache, JWTAuth $jwtAuth)
    {
        $this->cache = $cache;
        $this->user = $jwtAuth->toUser($jwtAuth->getToken());
    }

    protected function cacheKey($key)
    {
        return implode(':', [self::USER_CACHE_NAMESPACE, $this->user->id, $key]);
    }

    public function get($key)
    {
        return $this->cache->get($this->cacheKey($key));
    }

    // 1440 = 24 hours * 60 minutes --> cache TTLs for Laravel are in minutes.
    // when the TTL expires, the data won't be returned by the cache service anymore.
    // since it is "temp" data, this should be plenty of time for it to be cached.
    public function put($key, $value, $ttl = 1440)
    {
        $this->cache->put($this->cacheKey($key), $value, $ttl);
    }

}