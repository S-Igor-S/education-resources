<?php

return [
    "/start" => [
        "success" => "Hello {usernames}. I can help you to save useful info for skill-up",
        "wrong_message_format" => "{usernames}. Please, use next format for adding message to resources list: \"/save YOUR_URL\"",
    ],
    "/save" => [
        "success" => "Thanks {usernames}. Your resource was saved",
        "wrong_message_format" => "{usernames}, please, use next format for adding message to resources list: \"/save YOUR_URL\"",
        "incorrect_url" => "{usernames}, URL is incorrect",
        "resource_exist" => "{usernames}, resource already exist for current chat"
    ],
];
