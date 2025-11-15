<?php

declare(strict_types=1);

namespace App\Controllers;

use Larafony\Framework\Cache\Cache;
use Larafony\Framework\Routing\Advanced\Attributes\Route;
use Larafony\Framework\Web\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DebugBarController extends Controller
{
    #[Route('/debugbar/cache/clear', 'POST')]
    public function clearCache(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $cache = Cache::instance();
            $result = $cache->clear();

            return $this->json([
                'success' => $result,
                'message' => $result ? 'Cache cleared successfully' : 'Failed to clear cache'
            ]);
        } catch (\Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
