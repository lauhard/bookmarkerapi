<?php

declare(strict_types=1);

namespace App\Infrastruktur\Persistence;

use PDO;
use App\Domain\Bookmark\Bookmark;
use App\Domain\Bookmark\BookmarkRepositoryInterface;

class PostgresBookmarkRepository implements BookmarkRepositoryInterface
{
    public function __construct(private PDO $pdo) {}

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM bookmarker.bookmark ORDER BY id DESC");
        $rows = $stmt->fetchAll();

        return array_map(fn($row) => new Bookmark($row['id'], $row['page_title'], $row['url']), $rows);
    }
}
