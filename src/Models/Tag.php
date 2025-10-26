<?php

declare(strict_types=1);

namespace App\Models;

use Larafony\Framework\Database\ORM\Model;

class Tag extends Model
{
    public string $table { get => 'tags'; }

    public array $fillable = ['name'];

    public ?string $name {
        get => $this->name ?? null;
        set {
            $this->name = $value;
            $this->markPropertyAsChanged('name');
        }
    }

    public function notes(): array
    {
        return $this->belongsToMany(Note::class, 'note_tag', 'tag_id', 'note_id');
    }
}
