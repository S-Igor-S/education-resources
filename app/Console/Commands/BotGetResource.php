<?php

namespace App\Console\Commands;

use App\Enums\Bot;
use App\Services\Telegram\ServiceFacade;
use Exception;
use Illuminate\Console\Command;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class BotGetResource extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:bot-get-resource';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     * @throws Exception
     */
    public function handle(): void
    {
        $bot = new ServiceFacade();
        $bot->sendMessage(Bot::ResourceCommand->value);
    }
}
