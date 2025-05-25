<?php

declare(strict_types=1);

namespace App\Infrastruktur\Persistence;

use App\Application\Dto\Bookmark\BookmarkCreateUpdateDto;
use PDO;
use App\Domain\Bookmark\Entity\Bookmark;
use App\Domain\Bookmark\BookmarkRepositoryInterface;
use App\Domain\Bookmark\Factory\BookmarkFactory;

class PostgresBookmarkRepository implements BookmarkRepositoryInterface
{
    public function __construct(private PDO $pdo) {}

    /**
     * @param string $user_id
     * @return Bookmark[]
     */
    public function findByUserId(string $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM bookmarker.bookmark where user_id = :userId ORDER BY id DESC");
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $rows === false ? [] : $rows;
    }

    public function findById(string $id): ?Bookmark
    {
        $stmt = $this->pdo->prepare("SELECT * FROM bookmarker.bookmark WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }
        return BookmarkFactory::fromArrayToBookmark($row);
    }

    public function insert(BookmarkCreateUpdateDto $bookmark): string | null
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
}
