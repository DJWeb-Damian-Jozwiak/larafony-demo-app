<?php

use Larafony\Framework\Config\Environment\EnvReader;
use Larafony\Framework\DebugBar\Collectors\CacheCollector;
use Larafony\Framework\DebugBar\Collectors\PerformanceCollector;
use Larafony\Framework\DebugBar\Collectors\QueryCollector;
use Larafony\Framework\DebugBar\Collectors\RequestCollector;
use Larafony\Framework\DebugBar\Collectors\RouteCollector;
use Larafony\Framework\DebugBar\Collectors\TimelineCollector;
use Larafony\Framework\DebugBar\Collectors\ViewCollector;

return [
    'enabled' => EnvReader::read('APP_DEBUG', false),
    'collectors' => [
        'queries' => QueryCollector::class,
        'cache' => CacheCollector::class,
        'views' => ViewCollector::class,
        'route' => RouteCollector::class,
        'request' => RequestCollector::class,
        'performance' => PerformanceCollector::class,
        'timeline' => TimelineCollector::class,
    ]
];
