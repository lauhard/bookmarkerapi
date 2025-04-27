<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

class User
{
    // dieser Construktor verwendet das Property Promotion Feature von PHP 8.0
    public function __construct(
        private ?string $id,
        private string $email,
        private string $password,
        private string $nickname,
        private string $avatarUrl,
        private ?string $role,
        private ?\DateTimeImmutable $createdAt,
        private ?\DateTimeImmutable $updatedAt,
    ) {}



    //Funktionen um die Properties zu bekommen
    public function getId(): string
    {
        return $this->id;
    }
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
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }
}