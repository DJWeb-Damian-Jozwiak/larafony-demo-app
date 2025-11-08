<?php

declare(strict_types=1);

namespace App\Models;

use Larafony\Framework\Database\ORM\Attributes\HasMany;
use Larafony\Framework\Database\ORM\Entities\User as Authenticable;
use Larafony\Framework\Database\ORM\Model;

class User extends Authenticable
{
    /** @var array<int, Note> */
    #[HasMany(
        related: Note::class,
        foreign_key: 'note_id',
        local_key: 'id'
    )]
    public array $notes { get => $this->relations->getRelation('notes'); }

}
