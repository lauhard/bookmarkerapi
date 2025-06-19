<?php

declare(strict_types=1);

namespace App\Domain\Setting\Entity;

class SettingEntity
{
    public function __construct(
        private ?string $id = null,
        private string $user_id,
        private ?string $theme = 'dark',
        private bool $show_description = true,
        private bool $show_date = true,
        private bool $show_lists = true,
        private bool $show_tags = true,
        private ?\DateTimeImmutable $created_at = null,
        private ?\DateTimeImmutable $updated_at = null,
    ) {}

    /**
     * Getters for the SettingEntity properties.
     */
    public function getId(): ?string
    {
        return $this->id;
    }
    public function getUserId(): string
    {
        return $this->user_id;
    }
    public function getTheme(): ?string
    {
        return $this->theme;
    }
    public function isShowDescription(): bool
    {
        return $this->show_description;
    }
    public function isShowDate(): bool
    {
        return $this->show_date;
    }
    public function isShowLists(): bool
    {
        return $this->show_lists;
    }
    public function isShowTags(): bool
    {
        return $this->show_tags;
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
