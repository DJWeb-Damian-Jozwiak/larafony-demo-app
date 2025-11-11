<?php

declare(strict_types=1);

namespace App\ViewDto;

use App\Models\Comment;
use Larafony\Framework\Cache\Cache;
use Larafony\Framework\Clock\Contracts\Clock;
use Larafony\Framework\Core\Contracts\Arrayable;

readonly class CommentViewDto implements Arrayable
{
    public function __construct(
        public int $id,
        public string $content,
        public UserViewDto $user,
        public ?Clock $created_at,
    ) {
    }

    public static function fromModel(Comment $comment): self
    {
        return new self(
            id: $comment->id,
            content: $comment->content,
            user: UserViewDto::fromModel($comment->user),
            created_at: $comment->created_at,
        );
    }

    /**
     * @param Comment[] $comments
     * @return self[]
     */
    public static function collection(array $comments): array
    {
        return array_map(self::fromModel(...), $comments);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'user' => $this->user->toArray(),
            'created_at' => $this->created_at,
        ];
    }
}
