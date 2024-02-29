<?php

namespace App\Services;

use Closure;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Cache
{
    /**
     * @var Cache|null
     */
    private static ?self $instance = null;

    protected function __construct()
    {
    }

    protected function __clone()
    {
    }

    /**
     * @throws Exception
     */
    public function __wakeup()
    {
        throw new Exception("Object of class already exists");
    }

    /**
     * @return Cache|static
     */
    public static function getInstance(): Cache|static
    {
        if (null === self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * @param  string  $ValueName
     * @return Closure|int|mixed|object
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getValue(string $ValueName): mixed
    {
        return cache()->get($ValueName) ?? 0;
    }

    /**
     * @param  array  $updates
     * @param  int  $updateId
     * @param  string  $cacheValueName
     * @return void
     */
    public function cacheOffset(array $updates, int $updateId, string $cacheValueName): void
    {
        if (false === empty($updates)) {
            $lastUpdate = array_pop($updates);
            $updateId = $lastUpdate['update_id'];
        }
        cache()->put($cacheValueName, $updateId, 300);
    }
}
