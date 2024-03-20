<?php

namespace App\Repositories;

use App\Models\TelegramUser;

class TelegramUserRepository
{
    /**
     * @param  array  $update
     * @return int
     */
    public function save(array $update): int
    {
        $telegramUser = TelegramUser::firstOrCreate([
            'user_id' => $update['user_id']
        ], [
            'user_id' => $update['user_id'],
            'first_name' => $update['first_name'],
            'last_name' => $update['last_name'],
            'username' => '@'.$update['username'],
            'chat_ids' => json_encode([]),
        ]);

        $newChatsList = $this->updateUserChats(json_decode($telegramUser->chat_ids), $update['chat_id']);
        $this->update($update['user_id'], ['chat_ids' => $newChatsList]);

        return $telegramUser->id;
    }

    /**
     * @param  int  $id
     * @param  array  $updatedValues
     * @return void
     */
    public function update(int $id, array $updatedValues): void
    {
        TelegramUser::where('user_id', $id)
            ->update($updatedValues);
    }

    /**
     * @param  array  $currentUserChats
     * @param  int  $newChatId
     * @return false|string
     */
    private function updateUserChats(array $currentUserChats, int $newChatId): false|string
    {
        if (false === in_array($newChatId, $currentUserChats)) {
            $currentUserChats[] = $newChatId;
        }
        return json_encode($currentUserChats);
    }
}
