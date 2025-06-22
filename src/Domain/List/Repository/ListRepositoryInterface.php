<?php

declare(strict_types=1);

namespace App\Domain\List\Repository;

use App\Application\Dto\List\ListDto;
use App\Domain\List\Entity\ListEntity;

interface ListRepositoryInterface
{
    /**
     * Finds a list by its ID.
     *
     * @param string $listId The ID of the list.
     * @return ListEntity|null The list entity if found, null otherwise.
     */
    public function findById(string $listId): ?ListEntity;
    /**
     * Finds all lists for a given user ID.
     *
     * @param string $userId The ID of the user.
     * @return ListEntity[] An array of lists or null if no lists are found.
     */
    public function findByUserId(string $userId): array;

    /**
     * Finds lists by its bookmark ID.
     *
     * @param string $bookmarkId The ID of the bookmark.
     * @return ListEntity[]|null An array of list entities if found, null otherwise.
     */
    public function findByBookmarkId(string $bookmarkId): ?array;

    /**
     * Creates a new list for the user.
     *
     * @param ListDto $listDto The data transfer object containing list details.
     * @return ListEntity|null The created list data including its ID, or null if creation fails.
     */
    public function createList(ListDto $listDto): ?ListEntity;

    /**
     * Adds a bookmark to a list.
     *
     * @param string $listId The ID of the list to which the bookmark will be added.
     * @param string $bookmarkId The ID of the bookmark to be added.
     * @return bool True if the bookmark was successfully added, false otherwise.
     */
    public function createListBookmark(string $listId, string $bookmarkId): bool;

    public function updateListBookmark(string $bookmarkId, array $listIds): bool;

    public function deleteList(string $listId): bool;

    public function updateList(string $listId, array $data): ?string;
}
