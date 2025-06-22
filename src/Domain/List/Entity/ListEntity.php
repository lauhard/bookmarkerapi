<?php

declare(strict_types=1);

namespace App\Domain\List\Entity;

class ListEntity
{
    public function __construct(
        private ?string $id = null,
        private ?string $user_id = null,
        private ?string $name = null,
        private bool $is_public = false,
        private ?string $share_token = null,
        private ?\DateTimeImmutable $created_at = null,
        private ?\DateTimeImmutable $updated_at = null,
    ) {}

    /**
     * Getters for the ListEntity properties.
     */
    public function getId(): ?string
    {
        return $this->id;
    }
    public function getUserId(): ?string
    {
        return $this->user_id;
    }
    public function getName(): ?string
    {
        return $this->name;
    }
    public function isPublic(): bool
    {
        return $this->is_public;
    }
    public function getShareToken(): ?string
    {
        return $this->share_token;
    }
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }
}
