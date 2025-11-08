<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\DTOs\Auth\LoginDto;
use App\Models\User;
use Larafony\Framework\Auth\Auth;
use Larafony\Framework\Routing\Advanced\Attributes\Route;
use Larafony\Framework\Web\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LoginController extends Controller
{
    public function __construct()
    {
        parent::__construct(\Larafony\Framework\Web\Application::instance());
    }

    #[Route('/login', 'GET')]
    public function show(ServerRequestInterface $request): ResponseInterface
    {
        // If already authenticated, redirect to home
        if (Auth::check()) {
            return $this->redirect('/');
        }

        return $this->inertia('Auth/Login');
    }

    #[Route('/login', 'POST')]
    public function store(LoginDto $dto): ResponseInterface
    {
        // Find user by email
        $user = User::query()
            ->select()
            ->where('email', '=', $dto->email)
            ->first();

        if (!$user instanceof User) {
            return $this->json([
                'message' => 'Invalid credentials',
                'errors' => ['email' => ['These credentials do not match our records.']]
            ], 422);
        }

        // Attempt login with Auth facade (auto-regenerates session)
        if (Auth::attempt($user, $dto->password, $dto->remember)) {
            return $this->json([
                'message' => 'Login successful',
                'redirect' => '/'
            ]);
        }

        return $this->json([
            'message' => 'Invalid credentials',
            'errors' => ['email' => ['These credentials do not match our records.']]
        ], 422);
    }
}
