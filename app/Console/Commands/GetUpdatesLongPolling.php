<?php

namespace App\Console\Commands;

use App\Services\Telegram\Service;
use Illuminate\Console\Command;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class GetUpdatesLongPolling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-updates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var bool
     */
    protected bool $poll = true;

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $bot = new Service();
        while($this->poll === true) {
            try {
                $updates = $bot->getUpdates();
                if (true === $updates->isNotEmpty()) {
                    $bot->sendMessage($updates);
                }
            } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
                dd($e->getMessage());
            }
        }
    }
}
