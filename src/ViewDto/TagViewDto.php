<?php

declare(strict_types=1);

namespace App\ViewDto;

use App\Models\Tag;
use Larafony\Framework\Cache\Cache;
use Larafony\Framework\Core\Contracts\Arrayable;

readonly class TagViewDto implements Arrayable
{
    public function __construct(
        public int $id,
        public string $name,
    ) {
    }

    public static function fromModel(Tag $tag): self
    {
        return new self(
            id: $tag->id,
            name: $tag->name,
        );
    }

    /**
     * @param Tag[] $tags
     * @return self[]
     */
    public static function collection(array $tags): array
    {
        return array_map(self::fromModel(...), $tags);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
