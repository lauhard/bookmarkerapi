<?php

declare(strict_types=1);

namespace App\Application\Dto\User;

use JsonSerializable;
use App\Domain\User\Entity\User;

class UserResponseDto implements JsonSerializable
{
    public function __construct(
        public string $id,
        public string $email,
        public string $nickname,
        public ?string $avatar_url = null,
        public string $role = 'user',
        public ?string $token = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}


    //factory method to create an instance from a User object
    public static function fromUser(User $user, ?string $token = null): self
    {
        return new self(
            id: $user->getId(),
            email: $user->getEmail(),
            nickname: $user->getNickname(),
            avatar_url: $user->getAvatarUrl(),
            role: $user->getRole(),
            created_at: $user->getCreatedAt()?->format('Y-m-d H:i:s'),
            updated_at: $user->getUpdatedAt()?->format('Y-m-d H:i:s'),
            token: $token,
        );
    }

    //set token
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    //getters
    public function getId(): string
    {
        return $this->id;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getNickname(): string
    {
        return $this->nickname;
    }
    public function getAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }
    public function getRole(): string
    {
        return $this->role;
    }
    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }
    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'nickname' => $this->nickname,
            'avatar_url' => $this->avatar_url,
            'role' => $this->role,
            'token' => $this->token,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}