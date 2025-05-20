<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

class User
{
    // dieser Construktor verwendet das Property Promotion Feature von PHP 8.0
    public function __construct(
        private ?string $id = null,
        private string $email,
        private ?string $password,
        private string $name,
        private string $avatarUrl,
        private string $role = "user",
        private ?\DateTimeImmutable $createdAt,
        private ?\DateTimeImmutable $updatedAt,
    ) {}



    //Funktionen um die Properties zu bekommen
    public function getId(): string | null
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
    public function getName(): string
    {
        return $this->name;
    }
    public function getAvatarUrl(): string
    {
        return $this->avatarUrl;
    }
    public function getRole(): string
    {
        return $this->role;
    }
    public function getCreatedAt(): \DateTimeImmutable | null
    {
        return $this->createdAt;
    }
    public function getUpdatedAt(): \DateTimeImmutable | null
    {
        return $this->updatedAt;
    }
}
