<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface BotInterface
{
    /**
     * @param  Collection  $updates
     * @return void
     */
    public function sendMessage(Collection $updates):void;

    /**
     * @return Collection
     */
    public function getUpdates():Collection;
}
