<?php

namespace App\Services\TelegramBot;

use App\Enums\Bot;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class Updates
{
    /**
     * @param  int  $offset
     * @return array
     */
    public function get(int $offset = -1): array
    {
        return json_decode(Http::post(Bot::apiRequest('telegram')."getUpdates", [
            'allowed_updates' => ['message'],
            'offset' => $offset + 1
        ]), true)['result'];
    }

    /**
     * @param  array  $updates
     * @param  string  $updatesType
     * @return Collection
     */
    public function validate(array $updates, string $updatesType): Collection
    {
        if ($updatesType === Bot::GreetingCommand->value) {
            $updates = $this->getGreetingUpdates($updates);
        } elseif ($updatesType === Bot::ResourceCommand->value) {
            $updates = $this->getResourcesUpdates($updates);
        }
        return $this->removeDuplicates($updates);
    }

    /**
     * @param  Collection  $updates
     * @param  string  $command
     * @return Collection
     */
    public function sort(Collection $updates, string $command): Collection
    {
        if ($command === Bot::GreetingCommand->value) {
            $updates = collect(['success' => $updates]);
        } elseif ($command === Bot::ResourceCommand->value) {
            $updates = $updates->mapToGroups(function ($update) use ($command) {
                $explodedMessage = explode(' ', $update['text']);
                if (true === isset($explodedMessage[1]) && filter_var($explodedMessage[1], FILTER_VALIDATE_URL)) {
                    return ['success' => $update];
                }
                return ['wrong_message_format' => $update];
            });
        }

        return $updates;
    }

    /**
     * @param  array  $updates
     * @return Collection
     */
    private function removeDuplicates(array $updates): Collection
    {
        $updates = array_map(function ($update) {
            if (true === array_key_exists('from', $update['message']) ||
                true === array_key_exists('new_chat_member', $update['message'])) {
                return [
                    'chat_id' => $update['message']['chat']['id'],
                    'text' => $update['message']['text'] ?? '',
                    'username' => $update['message']['new_chat_member']['username'] ?? $update['message']['from']['username'] ?? '',
                ];
            }
            return null;
        }, $updates);
        return collect($updates)->unique();
    }

    /**
     * @param  array  $updates
     * @return array
     */
    private function getGreetingUpdates(array $updates): array
    {
        return array_filter($updates, function ($update) {
            $messageKeys = array_keys($update['message']);
            return true === in_array('new_chat_participant', $messageKeys) ||
                (true === isset($update['message']['text']) && $update['message']['text'] === '/start');
        });
    }

    /**
     * @param  array  $updates
     * @return array
     */
    private function getResourcesUpdates(array $updates): array
    {
        return array_filter($updates, function ($update) {
            return true === isset($update['message']['text']) && str_starts_with($update['message']['text'], '/save');
        });
    }
}
