<?php

declare(strict_types=1);

namespace App\Infrastruktur\Persistence;

use App\Application\Dto\List\ListDto;
use App\Domain\List\Entity\ListEntity;
use App\Domain\List\Factory\ListFactory;
use App\Domain\List\Repository\ListRepositoryInterface;
use PDO;

class PostgresListRepository implements ListRepositoryInterface
{
    public function __construct(private PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findById(string $listId): ?ListEntity
    {
        $stmt = $this->pdo->prepare('SELECT * FROM bookmarker.list WHERE id = :id');
        $stmt->bindValue(':id', $listId, \PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $data ? ListFactory::fromArrayToListEntity($data) : null;
    }

    /**
     * Finds all lists for a given user ID.
     *
     * @param string $userId The ID of the user.
     * @return ListEntity[] An array of lists or null if no lists are found.
     */
    public function findByUserId(string $userId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM bookmarker.list WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        $lists = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return ListFactory::fromArrayToListEntityCollection($lists);
    }


    /**
     * Creates a new list for the user.
     *
     * @param ListDto $listDto The data transfer object containing list details.
     * @return ListEntity|null The created list data including its ID, or null if creation fails.
     */
    public function createList(ListDto $listDto): ?ListEntity
    {
        $stmt = $this->pdo->prepare('INSERT INTO bookmarker.list (user_id, name, is_public)
            VALUES (:user_id, :name, :is_public)
            RETURNING id, user_id, name, is_public, share_token, created_at, updated_at');

        $stmt->bindValue(':user_id', $listDto->getUserId(), \PDO::PARAM_STR);
        $stmt->bindValue(':name', $listDto->getName(), \PDO::PARAM_STR);
        $stmt->bindValue(':is_public', $listDto->isPublic(), \PDO::PARAM_BOOL);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (empty($data)) {
            throw new \RuntimeException('Failed to create list');
        }
        return ListFactory::fromArrayToListEntity($data);
    }

    //list_bookmark relation
    /**
     * Adds a bookmark to a list.
     *
     * @param string $listId The ID of the list.
     * @param string $bookmarkId The ID of the bookmark.
     * @return bool True if the bookmark was added successfully, false otherwise.
     */
    public function createListBookmark(string $listId, string $bookmarkId): bool
    {
        //get last sort_order for the list
        $sortOrder = $this->getLasSortOrder($listId);
        $stmt = $this->pdo->prepare(
            'INSERT INTO bookmarker.list_bookmark (list_id, bookmark_id, sort_order)
            VALUES (:list_id, :bookmark_id, :sort_order)'
        );
        $stmt->bindValue(':list_id', $listId, \PDO::PARAM_STR);
        $stmt->bindValue(':bookmark_id', $bookmarkId, \PDO::PARAM_STR);
        $stmt->bindValue(':sort_order', $sortOrder, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Entfernt einen Bookmark aus genau einer Liste.
     *
     * @param string $listId UUID der Liste
     * @param string $bookmarkId UUID des Bookmarks
     * @return bool true bei Erfolg
     * @throws \RuntimeException bei Fehler
     */
    public function removeListBookmark(string $listId, string $bookmarkId): bool
    {
        try {
            $stmt = $this->pdo->prepare(
                'DELETE FROM bookmarker.list_bookmark
                WHERE list_id = :list_id AND bookmark_id = :bookmark_id'
            );

            $stmt->bindValue(':list_id', $listId, \PDO::PARAM_STR);
            $stmt->bindValue(':bookmark_id', $bookmarkId, \PDO::PARAM_STR);

            return $stmt->execute();
        } catch (\Throwable $e) {
            throw new \RuntimeException('Failed to remove bookmark from list: ' . $e->getMessage(), 0, $e);
        }
    }


    public function findByBookmarkId(string $bookmarkId): ?array
    {
        $stmt = $this->pdo->prepare('SELECT l.id, l.user_id, l.name, l.is_public, l.share_token, l.created_at, l.updated_at
            FROM bookmarker.list_bookmark lb
            JOIN bookmarker.list l ON lb.list_id = l.id
            WHERE lb.bookmark_id = :bookmark_id
        ');
        $stmt->bindValue(':bookmark_id', $bookmarkId, \PDO::PARAM_STR);
        $stmt->execute();
        $lists = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $lists ? ListFactory::fromArrayToListEntityCollection($lists) : null;
    }

    public function updateListBookmark(string $bookmarkId, array $listIds): bool
    {
        try {
            $this->pdo->beginTransaction();

            // 1. Aktuelle Listenbeziehungen laden
            $stmt = $this->pdo->prepare('SELECT list_id FROM bookmarker.list_bookmark WHERE bookmark_id = :id');
            $stmt->execute(['id' => $bookmarkId]);
            $current = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // array_diff gibt die Differenz zwischen den beiden Arrays zurück
            // Dies funktioniert wie folgt:
            // - $toRemove: Listen, die aktuell mit dem Bookmark verknüpft sind, aber nicht mehr in $listIds enthalten sind
            // - $toAdd: Listen, die in $listIds enthalten sind, aber aktuell nicht mit dem Bookmark verknüpft sind

            $toRemove = array_diff($current, $listIds); // prüfe, welche Listen entfernt werden müssen
            $toAdd = array_diff($listIds, $current); // prüfe, welche Listen hinzugefügt werden müssen

            // 2. Entfernen
            if (!empty($toRemove)) {
                foreach ($toRemove as $listId) {
                    $this->removeListBookmark($listId, $bookmarkId);
                }
            }

            // 3. Hinzufügen mit sort_order
            foreach ($toAdd as $listId) {
                if (!$this->createListBookmark($listId, $bookmarkId)) {
                    throw new \RuntimeException("Failed to insert bookmark into list $listId");
                }
            }

            $this->pdo->commit();
            return true;
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw new \RuntimeException('Failed to update list bookmark: ' . $e->getMessage(), 0, $e);
            // Optional: Loggen, z.B. mit Monolog oder ErrorHandler
            // error_log($e->getMessage());
        }
    }

    public function deleteList(string $listId): bool
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM bookmarker.list WHERE id = :id');
            $stmt->bindValue(':id', $listId, \PDO::PARAM_STR);
            return $stmt->execute();
        } catch (\Throwable $e) {
            throw new \RuntimeException('Failed to delete list: ' . $e->getMessage(), 0, $e);
        }
    }





    // ***************** HELPER FUNCTIONS ***************** //
    public function getLasSortOrder(string $listId): int
    {
        $stmt = $this->pdo->prepare('SELECT COALESCE(MAX(sort_order), 0) FROM bookmarker.list_bookmark WHERE list_id = :list_id');
        $stmt->bindValue(':list_id', $listId, \PDO::PARAM_STR);
        $stmt->execute();
        $maxSortOrder = $stmt->fetchColumn();

        return $maxSortOrder !== false ? (int)$maxSortOrder + 1 : 1; // Return 1 if no bookmarks exist
    }
}
