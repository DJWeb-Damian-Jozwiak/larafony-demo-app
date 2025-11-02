<?php

declare(strict_types=1);

namespace App\Middleware;

use Larafony\Framework\ErrorHandler\Backtrace;
use Larafony\Framework\View\ViewManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

class HandleInternalError implements MiddlewareInterface
{
    public function __construct(
        private readonly ViewManager $viewManager,
        private readonly bool $debug = false
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $exception) {
            return $this->renderError($exception);
        }
    }

    private function renderError(Throwable $exception): ResponseInterface
    {
        $statusCode = $this->getStatusCode($exception);

        if ($this->debug) {
            return $this->renderDebugView($exception, $statusCode);
        }

        return $this->renderProductionView($statusCode);
    }

    private function renderDebugView(Throwable $exception, int $statusCode): ResponseInterface
    {
        $backtrace = new Backtrace();
        $trace = $backtrace->generate($exception);

        $frames = array_map(function ($frame) {
            return [
                'file' => $frame->file,
                'line' => $frame->line,
                'class' => $frame->class,
                'function' => $frame->function,
                'snippet' => [
                    'lines' => $frame->snippet->lines,
                    'errorLine' => $frame->snippet->errorLine,
                ],
            ];
        }, $trace->frames);

        return $this->viewManager->make('errors.debug', [
            'exception' => [
                'class' => $exception::class,
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ],
            'backtrace' => $frames,
        ])->render()->withStatus($statusCode);
    }

    private function renderProductionView(int $statusCode): ResponseInterface
    {
        return $this->viewManager->make('errors.500')->render()->withStatus($statusCode);
    }

    private function getStatusCode(Throwable $exception): int
    {
        // Default to 500, can be extended for other HTTP exceptions
        return method_exists($exception, 'getStatusCode')
            ? $exception->getStatusCode()
            : 500;
    }
}
