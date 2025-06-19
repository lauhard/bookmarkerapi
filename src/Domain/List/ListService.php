<?php

declare(strict_types=1);

namespace App\Domain\List;

use App\Application\Dto\List\ListDto;
use App\Domain\List\Repository\ListRepositoryInterface;

class ListService
{
    public function __construct(
        private ListRepositoryInterface $listRepository
    ) {}

    /**
     * Retrieves a list of lists for a given user ID.
     *
     * @param string $userId The ID of the user whose lists are to be retrieved.
     * @return ListDto[]|null An array of ListDto objects representing the user's lists, or null if no lists are found.
     */
    public function getListForUser(string $userId): ?array
    {
        $list_collection = $this->listRepository->findByUserId($userId);
        if ($list_collection === null) {
            return null;
        } else {
            return ListDto::fromListEntityToArrayCollection($list_collection);
        }
    }

    /**
     * Creates a new list for the user.
     *
     * @param ListDto $listDto The data transfer object containing list details.
     * @return array|null The created list as an associative array, or null if creation fails.
     */
    public function createList(ListDto $listDto): ?array
    {
        // Create a new list for the user
        $listEntity = $this->listRepository->createList($listDto);
        if ($listEntity === null) {
            return null; // Return null if the list creation failed
        } else {
            return ListDto::fromEntityToArray($listEntity); // Convert the ListEntity to ListDto
        }
    }

    /**
     * Retrieves a list by its ID.
     *
     * @param string $listId The ID of the list to be retrieved.
     * @return array|null The list as an associative array, or null if not found.
     */
    public function getListById(string $listId): ?array
    {
        $listEntity = $this->listRepository->findById($listId);
        if ($listEntity === null) {
            return null;
        } else {
            return ListDto::fromEntityToArray($listEntity);
        }
    }

    /**
     * Updates the bookmark list for a given bookmark ID.
     *
     * @param string $bookmarkId The ID of the bookmark to update.
     * @param string|array $listIds A comma-separated string or an array of list IDs to associate with the bookmark.
     * @return bool True if the update was successful, false otherwise.
     */
    public function updateListBookmark(string $bookmarkId, string $listIds): bool
    {
        // check if $listIds is a string and convert it to an array
        if (is_string($listIds)) {
            $listIds = str_contains($listIds, ',') ? explode(',', $listIds) : [$listIds];
        }

        $listIds = array_filter(array_map('trim', (array)$listIds));

        $result = $this->listRepository->updateListBookmark($bookmarkId, $listIds);
        return $result; // Return the actual result of the update operation.
    }

    /**
     * Deletes a list by its ID.
     *
     * @param string $listId The ID of the list to be deleted.
     * @return bool True if the deletion was successful, false otherwise.
     */
    public function deleteList(string $listId): bool
    {
        return $this->listRepository->deleteList($listId);
    }
}
