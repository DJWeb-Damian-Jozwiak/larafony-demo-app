<?php

declare(strict_types=1);

namespace App\DTOs;

use Larafony\Framework\Validation\Attributes\IsValidated;
use Larafony\Framework\Validation\Attributes\MinLength;
use Larafony\Framework\Validation\Attributes\ValidWhen;
use Larafony\Framework\Validation\FormRequest;

class CreateNoteDto extends FormRequest
{
    #[IsValidated]
    #[MinLength(3)]
    public protected(set) string $title;
    #[IsValidated]
    #[MinLength(10)]
    public protected(set)  string $content;

    #[IsValidated]
    public protected(set) string|array|null $tags {
        get {
            if (!isset($this->tags)) {
                return null;
            }
            if (is_array($this->tags)) {
                return $this->tags;
            }
            return array_map('trim', explode(',', $this->tags));
        }
        set => $this->tags = $value;
    }
}
