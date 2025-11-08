<?php

declare(strict_types=1);

namespace App\ViewDto;

use App\Models\User;
use Larafony\Framework\Cache\Cache;
use Larafony\Framework\Core\Contracts\Arrayable;

readonly class UserViewDto implements Arrayable
{
    public function __construct(
        public ?int $id,
        public ?string $email = null,
    ) {
    }

    public static function fromModel(?User $user): self
    {
        if ($user === null) {
            return new self(null, 'Unknown');
        }

        $cache = Cache::instance();

        return $cache->remember("user.view.{$user->id}", 3600, function () use ($user) {
            return new self(
                id: $user->id,
                email: $user->email,
            );
        });
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email
        ] |> (static fn(array $data) => array_filter($data, fn($value) => $value !== null));
    }
}
