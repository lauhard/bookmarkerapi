<?php

declare(strict_types=1);

namespace App\Domain\Bookmark\Factory;

use App\Domain\Bookmark\Entity\Bookmark;

class BookmarkFactory
{
    public static function fromArrayToBookmark(array $data): Bookmark
    {
        return new Bookmark(
            id: $data['id'] ?? null,
            user_id: $data['user_id'],
            url: $data['url'],
            page_title: $data['page_title']
        );
    }
    public static function fromBookmarkToArray(Bookmark $bookmark): array
    {
        return [
            'id' => $bookmark->getId(),
            'user_id' => $bookmark->getUserId(),
            'url' => $bookmark->getUrl(),
            'page_title' => $bookmark->getTitle(),
        ];
    }
}
