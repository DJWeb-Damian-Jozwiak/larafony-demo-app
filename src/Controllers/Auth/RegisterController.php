<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\DTOs\Auth\RegisterDto;
use App\Models\User;
use Larafony\Framework\Auth\Auth;
use Larafony\Framework\Database\ORM\Entities\Role;
use Larafony\Framework\Routing\Advanced\Attributes\Route;
use Larafony\Framework\Web\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RegisterController extends Controller
{
    public function __construct()
    {
        parent::__construct(\Larafony\Framework\Web\Application::instance());
    }

    #[Route('/auth/register', 'GET')]
    public function show(ServerRequestInterface $request): ResponseInterface
    {
        // If already authenticated, redirect to home
        if (Auth::check()) {
            return $this->redirect('/');
        }

        return $this->inertia('Auth/Register');
    }

    #[Route('/auth/register', 'POST')]
    public function store(RegisterDto $dto): ResponseInterface
    {
        // Check if email already exists
        $existingUser = User::query()
            ->select()
            ->where('email', '=', $dto->email)
            ->first();

        if ($existingUser) {
            return $this->json([
                'message' => 'Validation failed',
                'errors' => ['email' => ['The email has already been taken.']]
            ], 422);
        }

        // Create new user
        $user = new User();
        $user->email = $dto->email;
        $user->username = $dto->username;
        $user->password = $dto->password; // Auto-hashed with Argon2id
        $user->is_active = 1;
        $user->save();

        // Assign default 'user' role
        $userRole = Role::query()
            ->select()
            ->where('name', '=', 'user')
            ->first();

        if ($userRole instanceof Role) {
            $user->addRole($userRole);
        }

        // Auto-login after registration (auto-regenerates session)
        Auth::login($user, remember: false);

        return $this->json([
            'message' => 'Registration successful',
            'redirect' => '/'
        ]);
    }
}
