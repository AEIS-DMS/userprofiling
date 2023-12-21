<?php

namespace App\Helpers;

use Closure;
use DateInterval;
use DateTimeInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

/**
 *
 */
class CacheHelper
{
    /**
     * Store an item in the cache.
     * @param string $key
     * @param mixed $value
     * @param null $tags
     * @param DateInterval|DateTimeInterface|int|null $ttl
     */
    public static function set(string $key, mixed $value, $tags = null, DateInterval|DateTimeInterface|int $ttl = null)
    {
        $isCacheEnable = self::cacheEnable();
        if (!$isCacheEnable){
            return;
        }

        Cache::put($key, $value, $ttl);
    }

    /**
     * Retrieve an item from the cache.
     * @param string $key
     * @param  mixed|null $default
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $isCacheEnable = self::cacheEnable();
        if (!$isCacheEnable){
            return false;
        }

        $cachedData = Cache::get($key, $default) ?? null;
        if ($cachedData){
            return json_decode($cachedData, true);
        }

        return $cachedData;
    }

    /**
     * Retrieve an item from the cache, or store the default value.
     * @param string $key
     * @param null $tags
     * @param DateInterval|DateTimeInterface|int|null $ttl
     * @param Closure $callback
     * @return mixed
     */
    public static function remember(string $key, DateInterval|DateTimeInterface|int|null $ttl, Closure $callback, $tags = null): mixed
    {
        $isCacheEnable = self::cacheEnable();
        if (!$isCacheEnable){
            return false;
        }

        return Cache::remember($key, $ttl, $callback);
    }

    /**
     * Remove an item from the cache.
     * @param  array|string $keys
     * @return bool
     */
    public static function forget(array|string $keys): bool
    {
        $isCacheEnable = self::cacheEnable();
        if (!$isCacheEnable){
            return false;
        }

        $keys = is_string($keys) ? [$keys] : $keys;

        foreach ($keys as $key){
            if (Str::startsWith($key, '*') && Str::endsWith($key, '*')){
                $matchedKeys = Redis::command('KEYS', [$key]);
                $cachedPrefix = config('database')['redis']['options']['prefix'].Cache::getPrefix();
                if (!empty($matchedKeys)) {
                    foreach ($matchedKeys as $matchedKey) {
                        Cache::forget(str_replace($cachedPrefix, '', $matchedKey));
                    }
                }
            }else{
                Cache::forget($key);
            }
        }

        return true;
    }

    /**
     * Remove all items from the cache.
     */
    public static function flush()
    {
        $isCacheEnable = self::cacheEnable();
        if (!$isCacheEnable){
            return;
        }

        Cache::flush();
    }

    /**
     * Remove all items with a given tag from the cache.
     * @param  array|string $tags
     */
    public static function flushByTags(array|string $tags)
    {
        $isCacheEnable = self::cacheEnable();
        if (!$isCacheEnable){
            return;
        }

        Cache::tags($tags)->flush();
    }

    /**
     * @return bool
     */
    public static function cacheEnable(): bool
    {
        return env('CACHE_ENABLE', false);
    }
}
