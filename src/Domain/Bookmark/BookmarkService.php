<?php
//bookmark action

declare(strict_types=1);

namespace App\Domain\Bookmark;

use App\Domain\Bookmark\BookmarkRepositoryInterface;


class BookmarkService
{
    private BookmarkRepositoryInterface $bookmarkRepository;

    public function __construct(BookmarkRepositoryInterface $bookmarkRepository)
    {
        $this->bookmarkRepository = $bookmarkRepository;
    }

    public function getAllBookmarks(): array
    {
        return $this->bookmarkRepository->findAll();
    }
}