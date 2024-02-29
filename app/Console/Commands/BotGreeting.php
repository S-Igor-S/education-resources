<?php

namespace App\Console\Commands;

use App\Enums\Bot;
use App\Services\Telegram\ServiceFacade;
use Illuminate\Console\Command;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class BotGreeting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:bot-greeting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $bot = new ServiceFacade();
        $bot->sendMessage(Bot::GreetingCommand->value);
    }
}
