<?php

declare(strict_types=1);

namespace App\Application\Dto\Auth;

class AuthResultDto
{
    public function __construct(
        public string $id,
        public string $email,
        public string $name,
        public string $role = 'user',
    ) {}

    public function getId(): string
    {
        return $this->id;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getRole(): string
    {
        return $this->role;
    }
}
