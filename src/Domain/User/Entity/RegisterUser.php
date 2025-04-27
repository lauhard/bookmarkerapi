<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

class RegisterUser
{
    // dieser Construktor verwendet das Property Promotion Feature von PHP 8.0
    public function __construct(
        private string $email,
        private string $password,
        private string $nickname,
        private string $avatarUrl,
        private string $role = 'user', //standard rolle 'user' vergibt postgres
    ) {}

    public function getEmail(): string
    {
        return $this->email;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function getNickname(): string
    {
        return $this->nickname;
    }
    public function getAvatarUrl(): string
    {
        return $this->avatarUrl;
    }
    public function getRole(): string
    {
        return $this->role;
    }
}
