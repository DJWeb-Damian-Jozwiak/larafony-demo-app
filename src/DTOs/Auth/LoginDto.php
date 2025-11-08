<?php

declare(strict_types=1);

namespace App\DTOs\Auth;

use Larafony\Framework\Validation\Attributes\Email;
use Larafony\Framework\Validation\Attributes\IsValidated;
use Larafony\Framework\Validation\Attributes\MinLength;
use Larafony\Framework\Validation\FormRequest;

class LoginDto extends FormRequest
{
    #[IsValidated]
    #[Email]
    public protected(set) string $email;

    #[IsValidated]
    #[MinLength(6)]
    public protected(set) string $password;

    private ?bool $_remember = null;

    public protected(set) bool $remember {
        get => $this->_remember ?? false;
        set => $this->_remember = $value;
    }
}
