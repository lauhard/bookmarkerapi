<?php

declare(strict_types=1);

namespace App\Domain\Bookmark;

use App\Application\Dto\Bookmark\BookmarkCreateUpdateDto;
use APP\Domain\Bookmark\Entity\Bookmark;

interface BookmarkRepositoryInterface
{
    /**
     * Find bookmarks by user ID.
     *
     * @param string $userId
     * @return Bookmark[]
     */
    public function findByUserId(string $userId): array;
    public function findById(string $id): Bookmark | null;
    public function insert(BookmarkCreateUpdateDto $bookmark): string | null;
    public function delete(string $id): bool;
    public function update(string $id, array $bookmark): string | null;
}
