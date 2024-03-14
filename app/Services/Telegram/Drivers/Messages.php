<?php

namespace App\Services\Telegram\Drivers;

use App\Enums\Bot;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class Messages
{
    /**
     * @param  Collection  $updates
     * @return void
     */
    public function send(Collection $updates): void
    {
        foreach ($updates as $status => $updateGroups) {
            foreach ($updateGroups as $update) {
                $username = '@' . $update['username'];
                Http::post(Bot::apiRequest('telegram')."sendMessage", [
                    'chat_id' => $update['chat_id'],
                    'text' => $this->getText($update['command'], $status, ['usernames' => $username]),
                ]);
            }
        }
    }

    /**
     * @param  string  $command
     * @param  string  $status
     * @param  array  $params
     * @return string
     */
    private function getText(string $command, string $status, array $params = []): string
    {
        $message = config('bot.'.$command)[$status] ?? '';
        foreach ($params as $search => $replace) {
            $message = str_replace('{' . $search . '}', $replace, $message);
        }

        return $message;
    }
}
