<?php

namespace App\Interfaces;

interface BotInterface
{
    /**
     * @param  string  $command
     * @return void
     */
    public function sendMessage(string $command):void;
}
