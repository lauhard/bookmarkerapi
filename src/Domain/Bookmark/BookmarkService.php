<?php
//bookmark action

declare(strict_types=1);

namespace App\Domain\Bookmark;

use App\Application\Dto\Bookmark\BookmarkDto;
use App\Domain\Bookmark\Repository\BookmarkRepositoryInterface;
use App\Application\Dto\Bookmark\ListBookmarkDto;
use App\Application\Dto\List\ListDto;
use App\Domain\Bookmark\Factory\BookmarkFactory;
use App\Domain\List\Repository\ListRepositoryInterface;

class BookmarkService
{
    private BookmarkRepositoryInterface $bookmarkRepository;
    private ListRepositoryInterface $listRepository;

    public function __construct(BookmarkRepositoryInterface $bookmarkRepository, ListRepositoryInterface $listRepository)
    {
        $this->bookmarkRepository = $bookmarkRepository;
        $this->listRepository = $listRepository;
    }

    /**
     * Return array of bookmarks for a user
     *
     * @param  string $userId
     * @return array|null
     */
    public function getBookmarksForUser(string $userId): array | null
    {
        $listBookmarkEntityCollection = $this->bookmarkRepository->findByUserId($userId);
        $listBookmarkDtoCollection = ListBookmarkDto::fromEntityToDtoCollection($listBookmarkEntityCollection);
        //merge the lists into the bookmarks
        $mergedListBookmarkDtoCollection = ListBookmarkDto::mergeBookmarkLists($listBookmarkDtoCollection);

        //map the merged ListBookmarkDto collection to an array collection
        //and unset the sort_order property
        //to avoid sending it to the client
        //this is the sort_order is not needed for all bookmarks without a specific list context
        $listBookmarkArrayCollection = array_map(function (ListBookmarkDto $listBookmarkDto) {
            $listBookmarkArray = ListBookmarkDto::fromDtoToArray($listBookmarkDto);
            $filtered_listBookmarkArray = ListBookmarkDto::unsetArrayProperties($listBookmarkArray, ['sort_order']);
            return $filtered_listBookmarkArray;
        }, $mergedListBookmarkDtoCollection);


        return $listBookmarkArrayCollection;
    }

    /**
     * Get bookmarks by list ID for a specific user.
     *
     * @param string $userId
     * @param string $listId
     * @return ListBookmarkDto[]
     */
    public function getBookmarksByListIdForUser(string $userId, string $listId): array
    {
        $listBookmarks = $this->bookmarkRepository->findByListIdAndUserId($listId, $userId);
        $listBookmarkArrayCollection = ListBookmarkDto::fromEntityToArrayCollection($listBookmarks);
        return $listBookmarkArrayCollection;
    }

    public function getBookmarkById(string $id): array | null
    {
        $bookmark = $this->bookmarkRepository->findById($id);
        if ($bookmark === null) {
            return null;
        }
        // Load lists for the bookmark
        $lists = $this->listRepository->findByBookmarkId($id);
        // Convert bookmark to listBookmarkDto and add lists to it
        if (empty($lists)) {
            // If no lists are found, return the bookmark without lists
            return BookmarkDto::fromBookmarkEntityToDto($bookmark)->fromDtoToArray();
        }
        $listBookmarkDtoCollection = ListBookmarkDto::fromBookmarkWithListToDto($bookmark, $lists);
        return ListBookmarkDto::fromDtoToArray($listBookmarkDtoCollection);
    }

    public function createBookmark(BookmarkDto $bookmark): string | null
    {
        $newBookarkID = $this->bookmarkRepository->insert($bookmark);
        return $newBookarkID;
    }

    public function deleteBookmark(string $id): bool
    {
        $res = $this->bookmarkRepository->delete($id);
        return $res;
    }

    public function updateBookmark(string $id, BookmarkDto $bookmark): string | null
    {
        $bookmarkArray = BookmarkFactory::fromDtoToUpdateArray($bookmark);
        $updatedBookmarkId = $this->bookmarkRepository->update($id, $bookmarkArray);
        return $updatedBookmarkId;
    }

    public function createBookmarkWithList(BookmarkDto $bookmark, string|null $listIds): string | null
    {
        // Convet the bookmarkListIds to an array if it's a string
        if (is_string($listIds)) {
            $listIds = str_contains($listIds, ',') ? explode(',', $listIds) : [$listIds];
        }

        $listIds = array_filter(array_map('trim', (array)$listIds));

        if (empty($listIds)) {
            //create default list
            $defaultListDto = new ListDto(
                userId: $bookmark->getUserId(),
                name: 'default',
                isPublic: false,
            );
            //check if the list already exists for the user
            $listCollection = $this->listRepository->findByUserId($defaultListDto->getUserId());
            //check for list with name default
            $defaultList = array_filter($listCollection, fn($list) => $list->getName() === 'default');
            if (empty($defaultList)) {
                $listDto = $this->listRepository->createList($defaultListDto);
                $listIds = [$listDto->getId()];
            } else {
                // Set the default listID into the listIds
                $listIds = [reset($defaultList)->getId()];
            }
        }

        // Create the bookmark
        $bookmarkId = $this->createBookmark($bookmark);
        if (!$bookmarkId) {
            throw new \RuntimeException('Bookmark creation failed');
        }

        // Add the bookmark to the specified lists
        foreach ($listIds as $listId) {
            $success = $this->listRepository->createListBookmark($listId, $bookmarkId);
            if (!$success) {
                throw new \RuntimeException("Failed to add bookmark {$bookmarkId} to list {$listId}");
            }
        }

        return $bookmarkId;
    }

    /**
     * Undocumented function
     *
     * @param string $userId
     * @param string $query
     * @return ?array
     */
    public function searchBookmarksForUser(string $userId, string $query): ?array
    {
        $listBookmarkEntityCollection =  $this->bookmarkRepository->searchForUser($userId, $query);
        $listBookmarkDtoCollection = ListBookmarkDto::fromEntityToDtoCollection($listBookmarkEntityCollection);
        //merge the lists into the bookmarks
        $mergedListBookmarkDtoCollection = ListBookmarkDto::mergeBookmarkLists($listBookmarkDtoCollection);

        //map the merged ListBookmarkDto collection to an array collection
        //and unset the sort_order property
        //to avoid sending it to the client
        //this is the sort_order is not needed for all bookmarks without a specific list context
        $listBookmarkArrayCollection = array_map(function (ListBookmarkDto $listBookmarkDto) {
            $listBookmarkArray = ListBookmarkDto::fromDtoToArray($listBookmarkDto);
            $filtered_listBookmarkArray = ListBookmarkDto::unsetArrayProperties($listBookmarkArray, ['sort_order']);
            return $filtered_listBookmarkArray;
        }, $mergedListBookmarkDtoCollection);
        return $listBookmarkArrayCollection;
    }
}
