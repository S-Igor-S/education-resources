<?php

namespace App\Services\TelegramBot;

use App\Interfaces\BotInterface;
use App\Services\Cache;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ServiceFacade implements BotInterface
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
     * @var Cache
     */
    private Cache $cache;

    public function __construct()
    {
        $this->updates = new Updates();
        $this->messages = new Messages();
        $this->cache = Cache::getInstance();
    }

    /**
     * @param  string  $command
     * @return void
     */
    public function sendMessage(string $command): void
    {
        $cacheValueName = 'update_id' . $command;

        try {
            $offset = $this->cache->getValue($cacheValueName);
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
            dd($e->getMessage());
        }

        $updates = $this->updates->get($offset ?? 0);
        $this->cache->cacheOffset($updates, $offset, $cacheValueName);
        $updates = $this->updates->validate($updates, $command);
        $updates = $this->updates->sort($updates, $command);
        $this->messages->send($updates, $command);
    }

}
