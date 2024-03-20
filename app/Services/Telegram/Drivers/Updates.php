<?php

namespace App\Services\Telegram\Drivers;

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
     * @return Collection
     */
    public function validate(array $updates): Collection
    {
        $botCommands = array_column(Bot::cases(), 'value');
        $updates = array_filter($updates, function ($update) use ($botCommands) {
            return true === isset($update['message']['text']) && true === $this->isCommand(
                    $update['message']['text'],
                    $botCommands
                );
        });
        return $this->removeDuplicates($updates);
    }

    /**
     * @param  Collection  $updates
     * @return Collection
     */
    public function sort(Collection $updates): Collection
    {
        return $updates->mapToGroups(function ($update) {
            if (true === $this->isCommand($update['text'], Bot::GreetingCommand->value)) {
                $update['command'] = Bot::GreetingCommand->value;
                return ['success' => $update];
            } elseif (true === $this->isCommand($update['text'], Bot::ResourceCommand->value)) {
                $explodedMessage = explode(' ', $update['text']);
                $update['command'] = Bot::ResourceCommand->value;
                if (true === isset($explodedMessage[1]) && filter_var($explodedMessage[1], FILTER_VALIDATE_URL)) {
                    return ['success' => $update];
                }
            }
            return ['wrong_message_format' => $update];
        });
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
                    'user_id' => $update['message']['from']['id'],
                    'chat_id' => $update['message']['chat']['id'],
                    'first_name' => $update['message']['from']['first_name'],
                    'last_name' => $update['message']['from']['last_name'],
                    'text' => $update['message']['text'] ?? '',
                    'username' => $update['message']['new_chat_member']['username'] ?? $update['message']['from']['username'] ?? '',
                ];
            }
            return null;
        }, $updates);
        return collect($updates)->unique();
    }

    /**
     * @param  string  $message
     * @param  array|string  $command
     * @return bool
     */
    private function isCommand(string $message, array|string $command): bool
    {
        $explodedMessage = explode(' ', $message);

        if (is_string($command) && $explodedMessage[0] === $command) {
            return true;
        } elseif (is_array($command) && true === in_array($explodedMessage[0], $command)) {
            return true;
        }
        return false;
    }
}
