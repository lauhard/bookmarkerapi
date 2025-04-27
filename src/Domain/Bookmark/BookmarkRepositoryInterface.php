<?php

declare(strict_types=1);

namespace App\Domain\Bookmark;

interface BookmarkRepositoryInterface
{
    public function findAll(): array;
}
