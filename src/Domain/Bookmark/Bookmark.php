<?php
//bookmark definition
declare(strict_types=1);

namespace App\Domain\Bookmark;

class Bookmark
{
    private ?int $id;
    private string $title;
    private string $url;

    //constructor
    public function __construct(?int $id, string $title, string $url)
    {
        $this->id    = $id;
        $this->title = $title;
        $this->url   = $url;
    }
    //getters
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getUrl(): string
    {
        return $this->url;
    }
}
