<?php
//bookmark definition
declare(strict_types=1);

namespace App\Domain\Bookmark\Entity;

class Bookmark
{
    //constructor
    public function __construct(
        private ?string $id = null,
        private string $user_id,
        private string $url,
        private string $page_title,
    ) {}
    //getters
    public function getId(): ?string
    {
        return $this->id;
    }
    public function getTitle(): string
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
}
