<?php
return [
    'artisan_path' => env('PARAVEL_ARTISAN_PATH', '/var/www/artisan'),
    'redis_connection' => env('PARAVEL_REDIS_CONNECTION', 'default'),
    'waiting_timeout' => env('PARAVEL_WAITING_TIMEOUT', 30)
];
