<?php

declare(strict_types=1);

namespace App\Models;

use Larafony\Framework\Database\ORM\Model;

class User extends Model
{
    public string $table { get => 'users'; }

    public array $fillable = ['name', 'email'];

    public ?string $name {
        get => $this->name ?? null;
        set {
            $this->name = $value;
            $this->markPropertyAsChanged('name');
        }
    }

    public ?string $email {
        get => $this->email ?? null;
        set {
            $this->email = $value;
            $this->markPropertyAsChanged('email');
        }
    }

    public function notes(): array
    {
        return $this->hasMany(Note::class, 'user_id');
    }
}
