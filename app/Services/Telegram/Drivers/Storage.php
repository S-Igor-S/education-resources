<?php

namespace App\Services\Telegram\Drivers;

use App\Models\Resource;
use App\Repositories\TelegramUserRepository;
use App\Services\UrlHelper;
use DOMDocument;
use Illuminate\Support\Collection;

class Storage
{
    private UrlHelper $urlHelper;

    public function __construct()
    {
        $this->urlHelper = UrlHelper::getInstance();
    }

    public function validate($updates)
    {
        $updates['success'] = $updates['success']->filter(function ($update) use ($updates) {
            $url = $this->getMessageUrl($update['text']);
            if (false === $this->urlHelper->isCorrectUrl($url)) {
                if (false === isset($updates['incorrect_url'])) {
                    $updates->put('incorrect_url', collect());
                }
                $updates['incorrect_url']->push($update);
                return false;
            } elseif (true === Resource::resourceExist($url, $update['chat_id'])) {
                if (false === isset($updates['resource_exist'])) {
                    $updates->put('resource_exist', collect());
                }
                $updates['resource_exist']->push($update);
                return false;
            }
            return true;
        });
        return $updates;
    }

    /**
     * @param  Collection  $updates
     * @return void
     */
    public function save(Collection $updates): void
    {
        $telegramUserRepository = new TelegramUserRepository();
        foreach ($updates as $update) {
            $url = $this->getMessageUrl($update['text']);
            $telegramUserId = $telegramUserRepository->save($update);

            Resource::firstOrCreate([
                'url' => $url,
                'chat_id' => $update['chat_id']
            ], [
                'telegram_user_id' => $telegramUserId,
                'chat_id' => $update['chat_id'],
                'title' => $this->getResourceTitle($url),
                'url' => $url,
                'status' => config('resources.statuses')['draft']
            ]);
        }
    }

    /**
     * @param  string  $url
     * @return string
     */
    private function getResourceTitle(string $url): string
    {
        $dom = new DOMDocument();

        $internalErrors = libxml_use_internal_errors(true);
        $html = $dom->loadHTMLFile($url);
        libxml_use_internal_errors($internalErrors);
        if (true === $html) {
            $elements = $dom->getElementsByTagName('title');
            if ($elements->length > 0) {
                return $elements->item(0)->textContent;
            }
        }
        return '';
    }

    /**
     * @param  string  $text
     * @return string
     */
    private function getMessageUrl(string $text): string
    {
        return explode(' ', $text)[1];
    }
}
