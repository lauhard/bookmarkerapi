<?php

declare(strict_types=1);

namespace App\Domain\Bookmark\Factory;

use App\Domain\Bookmark\Entity\ListBookmarkEntity;

class ListBookmarkFactory
{
    public static function fromArrayToListBookmarkEntity(array $data): ListBookmarkEntity
    {
        return new ListBookmarkEntity(
            id: $data['id'] ?? null,
            user_id: $data['user_id'] ?? null,
            url: $data['url'] ?? null,
            page_title: $data['page_title'] ?? null,
            page_capture: $data['page_capture'] ?? null,
            favicon_url: $data['favicon_url'] ?? null,
            screenshot_url: $data['screenshot_url'] ?? null,
            list_id: $data['list_id'] ?? null,
            name: $data['name'] ?? null,
            is_public: $data['is_public'] ?? false,
            sort_order: $data['sort_order'] ?? 0,
            createdAt: isset($data['created_at']) ? new \DateTimeImmutable($data['created_at']) : null,
            updatedAt: isset($data['updated_at']) ? new \DateTimeImmutable($data['updated_at']) : null,
        );
    }

    public static function fromListBookmarkEntityToArray(ListBookmarkEntity $bookmark): array
    {
        return [
            'id' => $bookmark->getId(),
            'user_id' => $bookmark->getUserId(),
            'url' => $bookmark->getUrl(),
            'page_title' => $bookmark->getTitle(),
            'page_capture' => $bookmark->getPageCapture(),
            'favicon_url' => $bookmark->getFaviconUrl(),
            'screenshot_url' => $bookmark->getScreenshotUrl(),
            'list_id' => $bookmark->getListId(),
            'name' => $bookmark->getListName(),
            'is_public' => $bookmark->isPublic(),
            'sort_order' => $bookmark->getSortOrder(),
            'created_at' => $bookmark->getCreatedAt()?->format('Y-m-d H:i:s'),
            'updated_at' => $bookmark->getUpdatedAt()?->format('Y-m-d H:i:s'),
        ];
    }

    public static function fromArrayToListBookmarkEntityCollection(array $data): array
    {
        return array_map(fn($item) => self::fromArrayToListBookmarkEntity($item), $data);
    }

    public static function fromListBookmarkEntityToArrayCollection(array $listBookmarks): array
    {
        return array_map(fn(ListBookmarkEntity $bookmark) => self::fromListBookmarkEntityToArray($bookmark), $listBookmarks);
    }
}
