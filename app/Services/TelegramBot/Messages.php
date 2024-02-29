<?php

namespace App\Services\TelegramBot;

use App\Enums\Bot;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class Messages
{
    /**
     * @param  Collection  $updates
     * @param  string  $message
     * @return void
     */
    public function send(Collection $updates, string $message): void
    {
        foreach ($updates as $command => $groupUpdates) {
            $uniqueChatIds = $groupUpdates->unique('chat_id');
            foreach ($uniqueChatIds as $uniqueChatId) {
                $chatId = $uniqueChatId['chat_id'];
                $currentChatUpdates = $groupUpdates->where('chat_id', $chatId);
                $usernames = '@' . $currentChatUpdates->pluck('username')->unique()->implode(', @');
                Http::post(Bot::apiRequest('telegram')."sendMessage", [
                    'chat_id' => $chatId,
                    'text' => $this->getText($message, $command, ['usernames' => $usernames]),
                ]);
            }
        }

    }

    /**
     * @param  string  $configKey
     * @param  string  $command
     * @param  array  $params
     * @return string
     */
    private function getText(string $configKey, string $command, array $params = []): string
    {
        $message = config('bot.'.$configKey)[$command] ?? '';
        foreach ($params as $search => $replace) {
            $message = str_replace('{' . $search . '}', $replace, $message);
        }

        return $message;
    }
}
