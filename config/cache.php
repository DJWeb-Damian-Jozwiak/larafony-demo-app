<?php

declare(strict_types=1);

use Larafony\Framework\Clock\Enums\TimeFormat;
use Larafony\Framework\Config\Environment\EnvReader;

return [
    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache store that will be used by the
    | framework. Supported: "file", "redis", "memcached"
    |
    */

    'default' => EnvReader::read('CACHE_DRIVER', 'redis'),

    /*
    |--------------------------------------------------------------------------
    | DebugBar DateTime Format
    |--------------------------------------------------------------------------
    |
    | Format for displaying cache expiry times in DebugBar
    |
    */

    'debugbar_format' => TimeFormat::DATETIME,

    /*
    |--------------------------------------------------------------------------
    | Cache Stores
    |--------------------------------------------------------------------------
    */

    'stores' => [

        'file' => [
            'driver' => 'file',
            'path' => EnvReader::read('CACHE_FILE_PATH', __DIR__ . '/../storage/cache'),
        ],

        'redis' => [
            'driver' => 'redis',
            'host' => EnvReader::read('REDIS_HOST', '127.0.0.1'),
            'port' => (int) EnvReader::read('REDIS_PORT', '6379'),
            'database' => (int) EnvReader::read('REDIS_CACHE_DB', '1'),
            'password' => EnvReader::read('REDIS_PASSWORD', null),
            'prefix' => EnvReader::read('REDIS_PREFIX', 'larafony:cache:'),
        ],

        'memcached' => [
            'driver' => 'memcached',
            'host' => EnvReader::read('MEMCACHED_HOST', '127.0.0.1'),
            'port' => (int) EnvReader::read('MEMCACHED_PORT', '11211'),
            'prefix' => EnvReader::read('MEMCACHED_PREFIX', 'larafony:cache:'),
        ],

    ],

];
