<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use Larafony\Framework\Auth\Auth;
use Larafony\Framework\Routing\Advanced\Attributes\Route;
use Larafony\Framework\Web\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LogoutController extends Controller
{
    public function __construct()
    {
        parent::__construct(\Larafony\Framework\Web\Application::instance());
    }

    #[Route('/logout', 'POST')]
    public function store(ServerRequestInterface $request): ResponseInterface
    {
        Auth::logout();

        return $this->redirect('/login');
    }
}
