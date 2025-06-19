<?php

declare(strict_types=1);

namespace App\Domain\Bookmark\Repository;

use App\Application\Dto\Bookmark\BookmarkDto;
use APP\Domain\Bookmark\Entity\Bookmark;
use App\Domain\Bookmark\Entity\BookmarkEntity;

interface BookmarkRepositoryInterface
{
    /**
     * Find bookmarks by user ID.
     *
     * @param string $userId
     * @return BookmarkEntity[]
     */
    public function findByUserId(string $userId): array;
    public function findById(string $id): BookmarkEntity | null;
    public function insert(BookmarkDto $bookmark): string | null;
    public function delete(string $id): bool;
    public function update(string $id, array $fields): string | null;

    /**
     * Search for bookmarks by list ID and user ID.
     *
     * @param string $listId
     * @param string $userId
     * @return ListBookmarkEntity[]
     */
    public function findByListIdAndUserId(string $listId, string $userId): array;

    /**
     * Search for bookmarks by user ID and query string.
     *
     * @param string $userId
     * @param string $query
     * @return ListBookmarkEntity[]
     */
    public function searchForUser(string $userId, string $query): array;
}
