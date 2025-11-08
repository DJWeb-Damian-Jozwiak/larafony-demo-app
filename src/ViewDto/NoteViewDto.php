<?php

declare(strict_types=1);

namespace App\ViewDto;

use App\Models\Note;
use Larafony\Framework\Cache\Cache;
use Larafony\Framework\Clock\Contracts\Clock;
use Larafony\Framework\Core\Contracts\Arrayable;

readonly class NoteViewDto implements Arrayable
{
    /**
     * @param TagViewDto[] $tags
     * @param CommentViewDto[] $comments
     */
    public function __construct(
        public int $id,
        public string $title,
        public string $content,
        public UserViewDto $user,
        public array $tags,
        public array $comments = [],
        public ?Clock $created_at = null,
        public ?Clock $updated_at = null,
    ) {
    }

    public static function fromModel(Note $note, bool $withComments = false): self
    {
        $cache = Cache::instance();
        $cacheKey = $withComments ? "note.view.full.{$note->id}" : "note.view.{$note->id}";

        return $cache->remember($cacheKey, 600, function () use ($note, $withComments) {
            return new self(
                id: $note->id,
                title: $note->title,
                content: $note->content,
                user: UserViewDto::fromModel($note->user),
                tags: TagViewDto::collection($note->tags ?? []),
                comments: $withComments ? CommentViewDto::collection($note->comments ?? []) : [],
                created_at: $note->created_at,
                updated_at: $note->updated_at,
            );
        });
    }

    /**
     * @param Note[] $notes
     * @return self[]
     */
    public static function collection(array $notes): array
    {
        return array_map(self::fromModel(...), $notes);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'user' => $this->user->toArray(),
            'tags' => array_map(fn(TagViewDto $tag) => $tag->toArray(), $this->tags),
            'comments' => array_map(fn(CommentViewDto $comment) => $comment->toArray(), $this->comments),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ] |> (static fn(array $data) => array_filter($data, fn($value) => $value !== null));
    }
}
