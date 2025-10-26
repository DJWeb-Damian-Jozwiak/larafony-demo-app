<?php

declare(strict_types=1);

namespace App\Models;

use Larafony\Framework\Database\ORM\Model;

class Comment extends Model
{
    public string $table { get => 'comments'; }

    public array $fillable = ['note_id', 'author', 'content'];

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

    public function note(): ?Note
    {
        return $this->belongsTo(Note::class, 'note_id');
    }
}
