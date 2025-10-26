<?php

declare(strict_types=1);

namespace App\Models;

use Larafony\Framework\Database\ORM\Attributes\BelongsTo;
use Larafony\Framework\Database\ORM\Attributes\BelongsToMany;
use Larafony\Framework\Database\ORM\Attributes\HasMany;
use Larafony\Framework\Database\ORM\Model;

class Note extends Model
{
    public string $table { get => 'notes'; }

    public array $fillable = ['title', 'content', 'user_id'];

    public ?string $title {
        get => $this->title ?? null;
        set {
            $this->title = $value;
            $this->markPropertyAsChanged('title');
        }
    }

    public ?string $content {
        get => $this->content ?? null;
        set {
            $this->content = $value;
            $this->markPropertyAsChanged('content');
        }
    }

    public ?int $user_id {
        get => $this->user_id ?? null;
        set {
            $this->user_id = $value;
            $this->markPropertyAsChanged('user_id');
        }
    }

    // BelongsTo relationship: Note belongs to User
    #[BelongsTo(
        related: User::class,
        foreign_key: 'user_id',
        local_key: 'id'
    )]
    public ?User $user { get => $this->relations->getRelation('user'); }

    // HasMany relationship: Note has many Comments
    #[HasMany(
        related: Comment::class,
        foreign_key: 'note_id',
        local_key: 'id'
    )]
    public array $comments { get => $this->relations->getRelation('comments'); }

    // BelongsToMany relationship: Note belongs to many Tags
    #[BelongsToMany(
        related: Tag::class,
        pivot_table: 'note_tag',
        foreign_pivot_key: 'note_id',
        related_pivot_key: 'tag_id'
    )]
    public array $tags { get => $this->relations->getRelation('tags'); }

    /**
     * Attach tags to this note
     *
     * @param array<int> $tagIds
     */
    public function attachTags(array $tagIds): void
    {
        /** @var \Larafony\Framework\Database\ORM\Relations\BelongsToMany $relation */
        $relation = $this->relations->getRelationInstance('tags');
        $relation->attach($tagIds);
    }
}
