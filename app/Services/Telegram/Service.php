<?php

namespace App\Services\Telegram;

use App\Interfaces\BotInterface;
use App\Services\Cache;
use App\Services\Telegram\Drivers\Messages;
use App\Services\Telegram\Drivers\Storage;
use App\Services\Telegram\Drivers\Updates;
use Illuminate\Support\Collection;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Service implements BotInterface
{
    /**
     * @var Updates
     */
    private Updates $updates;

    /**
     * @var Messages
     */
    private Messages $messages;

    /**
     * @var Storage
     */
    private Storage $storage;

    /**
     * @var Cache
     */
    private Cache $cache;

    public function __construct()
    {
        $this->updates = new Updates();
        $this->messages = new Messages();
        $this->storage = new Storage();
        $this->cache = Cache::getInstance();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getUpdates(): Collection
    {
        $cacheValueName = 'last_message_id';
        $offset = $this->cache->getValue($cacheValueName) ?? 0;
        $updates = $this->updates->get($offset);
        $this->cache->cacheOffset($updates, $offset, $cacheValueName);
        $updates = $this->updates->validate($updates);
        return $this->updates->sort($updates);
    }

    /**
     * @param  Collection  $updates
     * @return void
     */
    public function sendMessage(Collection $updates): void
    {
        $this->messages->send($updates);
    }

    /**
     * @param  Collection  $updates
     * @return Collection
     */
    public function save(Collection $updates): Collection
    {
        $updates = $this->storage->validate($updates);
        $this->storage->save($updates['success']);
        return $updates;
    }

}
