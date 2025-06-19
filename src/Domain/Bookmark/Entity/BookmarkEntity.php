<?php
//bookmark definition
declare(strict_types=1);

namespace App\Domain\Bookmark\Entity;

class BookmarkEntity
{
    //constructor
    public function __construct(
        private ?string $id = null,
        private string $user_id,
        private string $url,
        private string $page_title,
        private ?string $page_capture = null,
        private ?string $favicon_url = null,
        private ?string $screenshot_url = null,
        private ?\DateTimeImmutable $created_at,
        private ?\DateTimeImmutable $updated_at,
    ) {}

    //getters
    public function getId(): ?string
    {
        return $this->id;
    }
    public function getPageTitle(): string
    {
        return $this->page_title;
    }
    public function getUrl(): string
    {
        return $this->url;
    }
    public function getUserId(): string
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
        return $this->created_at;
    }
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }
}
