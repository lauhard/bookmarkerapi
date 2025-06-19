<?php

declare(strict_types=1);

namespace App\Infrastruktur\Persistence;

use App\Application\Dto\Bookmark\BookmarkDto;
use PDO;
use App\Domain\Bookmark\Entity\BookmarkEntity;
use App\Domain\Bookmark\Repository\BookmarkRepositoryInterface;
use App\Domain\Bookmark\Factory\BookmarkFactory;
use App\Domain\Bookmark\Factory\ListBookmarkFactory;

class PostgresBookmarkRepository implements BookmarkRepositoryInterface
{
    public function __construct(private PDO $pdo) {}



    /**
     * Find a bookmark by its ID.
     *
     * @param string $id The ID of the bookmark.
     * @return BookmarkEntity|null The bookmark entity or null if not found.
     */
    public function findById(string $id): ?BookmarkEntity
    {
        $stmt = $this->pdo->prepare("SELECT * FROM bookmarker.bookmark WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $bookmark = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($bookmark === false) {
            return null;
        }
        return BookmarkFactory::fromArrayToBookmarkEntity($bookmark);
    }

    /**
     * @param string $user_id
     * @return ListBookmark[]
     */
    public function findByUserId(string $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                b.*,
                l.name,
                l.is_public,
                lb.list_id
            FROM bookmarker.bookmark b
            LEFT JOIN bookmarker.list_bookmark lb
                ON b.id = lb.bookmark_id
            LEFT JOIN bookmarker.list l
                ON l.id = lb.list_id
            WHERE b.user_id = :userId
            ORDER BY b.id DESC
        ");
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();
        $bookmarkArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ListBookmarkFactory::fromArrayToListBookmarkEntityCollection($bookmarkArray);
    }

    /**
     * Search for bookmarks by list ID and user ID.
     *
     * @param string $listId
     * @param string $userId
     * @return ListBookmarkEntity[]
     * @throws \PDOException
     */
    public function findByListIdAndUserId(string $listId, string $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT b.*, lb.sort_order, l.name, l.is_public, lb.list_id
            FROM bookmarker.list_bookmark lb
            JOIN bookmarker.bookmark b ON b.id = lb.bookmark_id
            JOIN bookmarker.list l ON l.id = lb.list_id
            WHERE lb.list_id = :listId
            AND l.user_id = :userId
            ORDER BY lb.sort_order DESC, b.created_at DESC
        ");
        $stmt->bindValue(':listId', $listId);
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();
        $listBookmarks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ListBookmarkFactory::fromArrayToListBookmarkEntityCollection($listBookmarks);
    }

    public function insert(BookmarkDto $bookmark): string | null
    {
        $stmt = $this->pdo->prepare("INSERT INTO bookmarker.bookmark (user_id, page_title, url) VALUES (:userId, :page_title, :url) RETURNING id");
        $stmt->bindValue(':userId', $bookmark->getUserId());
        $stmt->bindValue(':page_title', $bookmark->getPageTitle());
        $stmt->bindValue(':url', $bookmark->getUrl());
        $stmt->execute();
        $id = $stmt->fetchColumn();
        if ($id === false) {
            return null;
        } else {
            return (string)$id;
        }
    }

    public function delete(string $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM bookmarker.bookmark WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function update(string $id, array $fields): string | null
    {
        //update just the fields that are set
        $setParts = [];
        foreach ($fields as $key => $value) {
            $setParts[] = "$key = :$key";
        }
        $setClause = implode(', ', $setParts);

        $sql = "UPDATE bookmarker.bookmark SET $setClause WHERE id = :id RETURNING id";
        $stmt = $this->pdo->prepare($sql);

        // Werte binden
        foreach ($fields as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $id = $stmt->fetchColumn();
        return $id !== false ? (string)$id : null;
    }

    /**
     * Search for bookmarks by user ID and query string.
     *
     * @param string $userId
     * @param string $query
     * @return ListBookmarkEntity[]
     */
    public function searchForUser(string $userId, string $query): array
    {
        $sql = "
            SELECT
                b.*,
                l.name,
                l.is_public,
                lb.list_id,
                GREATEST(
                    ts_rank(b.search_vector_en, plainto_tsquery('english', :query)),
                    ts_rank(b.search_vector_de, plainto_tsquery('german', :query))
                ) AS rank
            FROM bookmarker.bookmark b
            LEFT JOIN bookmarker.list_bookmark lb ON b.id = lb.bookmark_id
            LEFT JOIN bookmarker.list l ON l.id = lb.list_id
            WHERE b.user_id = :userId
            AND (
                b.search_vector_en @@ plainto_tsquery('english', :query)
                OR
                b.search_vector_de @@ plainto_tsquery('german', :query)
                OR
                b.url ILIKE :likeQuery
                OR
                b.page_title ILIKE :likeQuery
            )
            ORDER BY rank DESC
            LIMIT 50
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':userId', $userId);
        $stmt->bindValue(':query', "$query");
        $stmt->bindValue(':likeQuery', '%' . $query . '%');
        $stmt->execute();
        $bookmarkArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ListBookmarkFactory::fromArrayToListBookmarkEntityCollection($bookmarkArray);
    }
}
