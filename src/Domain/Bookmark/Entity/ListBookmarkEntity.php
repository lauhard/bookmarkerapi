<?php

declare(strict_types=1);

namespace App\Domain\Bookmark\Entity;

class ListBookmarkEntity
{
    public function __construct(
        private ?string $id = null,
        private ?string $user_id = null,
        private ?string $url = null,
        private ?string $page_title = null,
        private ?string $page_capture = null,
        private ?string $favicon_url = null,
        private ?string $screenshot_url = null,
        private ?string $list_id = null,
        private ?string $name = null,
        private ?bool $is_public = false,
        private ?int $sort_order = 0,
        private ?\DateTimeImmutable $createdAt,
        private ?\DateTimeImmutable $updatedAt,
    ) {}

    public function getId(): ?string
    {
        return $this->id;
    }
    public function getPageTitle(): ?string
    {
        return $this->page_title;
    }
    public function getUrl(): ?string
    {
        return $this->url;
    }
    public function getUserId(): ?string
    {
        return $this->user_id;
    }
    public function getPageCapture(): ?string
    {
        return $this->page_capture;
    }
    public function getFaviconUrl(): ?string
    {
        return $this->favicon_url;
    }
    public function getScreenshotUrl(): ?string
    {
        return $this->screenshot_url;
    }
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
    public function getListId(): ?string
    {
        return $this->list_id;
    }
    public function getSortOrder(): int
    {
        return $this->sort_order ?? 0;
    }
    public function isPublic(): bool
    {
        return $this->is_public ?? false;
    }
    public function getListName(): ?string
    {
        return $this->name;
    }
}
