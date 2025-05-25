<?php
//bookmark action

declare(strict_types=1);

namespace App\Domain\Bookmark;

use App\Application\Dto\Bookmark\BookmarkCreateUpdateDto;
use App\Domain\Bookmark\BookmarkRepositoryInterface;
use App\Domain\Bookmark\Entity\Bookmark;
use App\Domain\Bookmark\Factory\BookmarkFactory;

class BookmarkService
{
    private BookmarkRepositoryInterface $bookmarkRepository;

    public function __construct(BookmarkRepositoryInterface $bookmarkRepository)
    {
        $this->bookmarkRepository = $bookmarkRepository;
    }
    /**
     *
     * return arrray of bookmarks for a user
     *
     * @param  $userId
     * @return array
     */
    public function getBookmarksForUser(string $userId): array | null
    {
        $bookmarks = $this->bookmarkRepository->findByUserId($userId);
        $foo = array_map(function ($bookmark) {
            return [
                'id' => $bookmark['id'],
                'user_id' => $bookmark['user_id'],
                'url' => $bookmark['url'],
                'page_title' => $bookmark['page_title'],
            ];
        }, $bookmarks);
        return $bookmarks;
    }

    public function getBookmarkById(string $id): array | null
    {
        // Add logic to retrieve a bookmark by its ID
        // This could involve fetching from a database
        $bookmark = $this->bookmarkRepository->findById($id);
        return $bookmark ? BookmarkFactory::fromBookmarkToArray($bookmark) : null;
    }

    public function createBookmark(BookmarkCreateUpdateDto $bookmark): string | null
    {
        // Add logic to add a bookmark
        $newBookarkID = $this->bookmarkRepository->insert($bookmark);
        return $newBookarkID;
    }

    public function deleteBookmark(string $id): bool
    {
        $res = $this->bookmarkRepository->delete($id);
        return $res;
    }

    public function updateBookmark(string $id, BookmarkCreateUpdateDto $bookmark): string | null
    {
        $updateDto = $bookmark->toUpdateArray();
        $updatedBookmarkId = $this->bookmarkRepository->update($id, $updateDto);
        return $updatedBookmarkId;
    }
}
