<?php
//bookmark definition
declare(strict_types=1);

namespace App\Domain\Bookmark;

class Bookmark
{
    private ?string $id;
    private string $page_title;
    private string $url;

    //constructor
    public function __construct(?string $id, string $page_title, string $url)
    {
        $this->id    = $id;
        $this->page_title = $page_title;
        $this->url = $url;
    }
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
}
