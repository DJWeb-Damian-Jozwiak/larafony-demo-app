<?php

declare(strict_types=1);

namespace App\DTOs\Auth;

use Larafony\Framework\Validation\Attributes\Email;
use Larafony\Framework\Validation\Attributes\IsValidated;
use Larafony\Framework\Validation\Attributes\MinLength;
use Larafony\Framework\Validation\Attributes\NotCompromised;
use Larafony\Framework\Validation\FormRequest;

class RegisterDto extends FormRequest
{
    #[IsValidated]
    #[MinLength(3)]
    public protected(set) string $username;

    #[IsValidated]
    #[Email]
    public protected(set) string $email;

    #[IsValidated]
    #[MinLength(8)]
    #[NotCompromised(threshold: 0)]
    public protected(set) string $password;
}
