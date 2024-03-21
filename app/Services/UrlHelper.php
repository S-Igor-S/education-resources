<?php

namespace App\Services;

use Exception;

class UrlHelper
{
    /**
     * @var UrlHelper|null
     */
    private static ?self $instance = null;

    protected function __construct()
    {
    }

    protected function __clone()
    {
    }

    /**
     * @throws Exception
     */
    public function __wakeup()
    {
        throw new Exception("Object of class already exists");
    }

    /**
     * @return Cache|static
     */
    public static function getInstance(): Cache|static
    {
        if (null === self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * @param  string  $url
     * @return bool
     */
    public function isCorrectUrl(string $url): bool
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        $result = curl_exec($curl);

        if ($result !== false) {
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($statusCode === 404) {
                return false;
            } else {
                return true;
            }
        }

        return false;
    }
}
