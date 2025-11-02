<?php

declare(strict_types=1);

namespace App\Middleware;

use Larafony\Framework\Core\Exceptions\NotFoundError;
use Larafony\Framework\View\ViewManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HandleNotFound implements MiddlewareInterface
{
    public function __construct(
        private readonly ViewManager $viewManager,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (NotFoundError $e) {
            return $this->viewManager->make('errors.404', [
                'method' => $request->getMethod(),
                'path' => $request->getUri()->getPath(),
            ])->render()->withStatus(404);
        }
    }
}
