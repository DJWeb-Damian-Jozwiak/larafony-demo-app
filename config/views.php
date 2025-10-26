<?php

declare(strict_types=1);

use Larafony\Framework\Config\Environment\EnvReader;

return [
    'default' => EnvReader::read('VIEW_ENGINE', 'blade'),

    'engines' => [
        'blade' => [
            'paths' => [
                'template_path' => __DIR__ . '/../resources/views/blade',
                'cache_path' => __DIR__ . '/../storage/cache/blade',
            ],
            'components' => [
                'namespace' => '\\App\\View\\Components\\',
            ]
        ]
    ],
];
