<?php

return [
    "commands" => [
        "greeting" => "/start",
        "resource" => "/save"
    ],
    "greeting" => [
        "success" => "Hello {usernames}. I can help you to save useful info for skill-up",
        "wrong_message_format" => "{usernames}. Please, use next format for adding message to resources list: \"/save YOUR_URL\"",
    ],
    "resource" => [
        "success" => "Thanks {usernames}. Your resource was saved",
        "wrong_message_format" => "{usernames}. Please, use next format for adding message to resources list: \"/save YOUR_URL\"",
    ],
];
