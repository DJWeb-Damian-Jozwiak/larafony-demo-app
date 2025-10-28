<?php

use Larafony\Framework\Http\Middleware\InertiaMiddleware;
use Larafony\Framework\Web\Middleware\HandleNotFound;

return [
    'before_global' => [
        HandleNotFound::class,
    ],
    'global' => [
        InertiaMiddleware::class,
    ],
    'after_global' => [],
];