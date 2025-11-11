<?php

use App\Middleware\HandleInternalError;
use App\Middleware\HandleNotFound;
use Larafony\Framework\DebugBar\Middleware\InjectDebugBar;
use Larafony\Framework\Http\Middleware\InertiaMiddleware;

return [
    'before_global' => [
       // HandleNotFound::class,
       // HandleInternalError::class,
    ],
    'global' => [
        InertiaMiddleware::class,
    ],
    'after_global' => [
        InjectDebugBar::class, // Must be last to inject into final response
    ],
];