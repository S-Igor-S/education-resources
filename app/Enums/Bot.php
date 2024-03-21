<?php

namespace App\Enums;

enum Bot: string
{
    case GreetingCommand = '/start';

    case ResourceCommand = '/save';

    public static function apiRequest(string $api = ''): string
    {
        return match(true) {
            $api === 'telegram' => "https://".env('TELEGRAM_BOT_HOST')."/".env('TELEGRAM_BOT_TOKEN')."/",
            default => ''
        };
    }
}
