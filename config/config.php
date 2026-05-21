<?php

return [
    'app' => [
        'base_url' => getenv('APP_BASE_URL') ?: 'http://localhost/myTest/public',
    ],
    'db' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => (int)(getenv('DB_PORT') ?: 3306),
        'name' => getenv('DB_NAME') ?: 'blog',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: '',
        'charset' => getenv('DB_CHARSET') ?: 'utf8mb4',
    ],
    'blog' => [
        'category_page_size' => 6,
        'home_posts_per_category' => 3,
        'similar_posts_limit' => 3,
    ],
];
