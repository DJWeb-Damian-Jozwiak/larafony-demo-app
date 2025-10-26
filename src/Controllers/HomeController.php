<?php

declare(strict_types=1);

namespace App\Controllers;

use Larafony\Framework\Routing\Advanced\Attributes\Route;
use Larafony\Framework\Web\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HomeController extends Controller
{
    #[Route('/', 'GET')]
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('home', [
            'title' => 'Welcome to Larafony Notes Pro+'
        ]);
    }
}
