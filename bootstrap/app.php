<?php

declare(strict_types=1);

use Larafony\Framework\Auth\ServiceProviders\AuthServiceProvider;
use Larafony\Framework\Config\ServiceProviders\ConfigServiceProvider;
use Larafony\Framework\Database\ServiceProviders\DatabaseServiceProvider;
use Larafony\Framework\ErrorHandler\ServiceProviders\ErrorHandlerServiceProvider;
use Larafony\Framework\Http\ServiceProviders\HttpServiceProvider;
use Larafony\Framework\Routing\ServiceProviders\RouteServiceProvider;
use Larafony\Framework\Storage\ServiceProviders\SessionServiceProvider;
use Larafony\Framework\View\ServiceProviders\ViewServiceProvider;
use Larafony\Framework\Web\ServiceProviders\WebServiceProvider;

require_once __DIR__ . '/../vendor/autoload.php';

$app = \Larafony\Framework\Web\Application::instance(base_path: dirname(__DIR__));

$app->withServiceProviders([
    
    ConfigServiceProvider::class,
    DatabaseServiceProvider::class,
    HttpServiceProvider::class,
    RouteServiceProvider::class,
    ViewServiceProvider::class,
    WebServiceProvider::class,
    ErrorHandlerServiceProvider::class, // Must be last to ensure ViewManager is available
    AuthServiceProvider::class,
    SessionServiceProvider::class,
]);

$app->withRoutes(function ($router) {
    $router->loadAttributeRoutes(__DIR__ . '/../src/Controllers');
});

return $app;

