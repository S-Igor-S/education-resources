<?php

namespace App\Enums;

enum Bot: string
{
    case GreetingCommand = 'greeting';

    case ResourceCommand = 'resource';

    public static function apiRequest(string $api = ''): string
    {
        return match(true) {
            $api === 'telegram' => "https://".env('TELEGRAM_BOT_HOST')."/".env('TELEGRAM_BOT_TOKEN')."/",
            default => ''
        };
    }
}
