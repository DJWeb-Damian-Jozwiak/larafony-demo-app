<?php

declare(strict_types=1);

namespace App\Models;

use Larafony\Framework\Clock\Contracts\Clock;
use Larafony\Framework\Database\ORM\Attributes\BelongsTo;
use Larafony\Framework\Database\ORM\Model;

class Comment extends Model
{
    public string $table { get => 'comments'; }

    public ?int $note_id {
        get => $this->note_id ?? null;
        set {
            $this->note_id = $value;
            $this->markPropertyAsChanged('note_id');
        }
    }

    public ?string $author {
        get => $this->author ?? null;
        set {
            $this->author = $value;
            $this->markPropertyAsChanged('author');
        }
    }

    public ?string $content {
        get => $this->content ?? null;
        set {
            $this->content = $value;
            $this->markPropertyAsChanged('content');
        }
    }

    #[BelongsTo(
        related: User::class,
        foreign_key: 'user_id',
        local_key: 'id'
    )]
    public ?User $user { get => $this->relations->getRelation('user'); }

    public Clock $created_at {
        get => $this->created_at;
        set {
            $this->created_at = $value;
            $this->markPropertyAsChanged('created_at');
        }
    }
    public Clock $updated_at {
        get => $this->updated_at;
        set {
            $this->updated_at = $value;
            $this->markPropertyAsChanged('updated_at');
        }
    }

    protected array $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
