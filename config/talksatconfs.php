<?php

declare(strict_types=1);

return [
    'database' => [
        'connection' => env('DB_TAC_CONNECTION'),
        'name' => env('DB_TAC_DATABASE'),
    ],

    'name' => env('TAC_APP_NAME', 'talksatconfs.com'),
    'sitemap_path' => 'sitemaps/tac',

    'conferences' => [
        'messages' => [
            'no_records' => 'No conferences found',
        ],
    ],

    'events' => [
        'messages' => [
            'no_records' => 'No events found',
        ],
    ],

    'talks' => [
        'messages' => [
            'no_records' => 'No talks found',
        ],
    ],

    'speakers' => [
        'messages' => [
            'no_records' => 'No speakers found',
        ],
    ],

];
