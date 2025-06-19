<?php

declare(strict_types=1);

namespace App\Domain\Bookmark\Factory;

use App\Application\Dto\Bookmark\BookmarkDto;
use App\Domain\Bookmark\Entity\BookmarkEntity;

class BookmarkFactory
{
    public static function fromArrayToBookmarkEntity(array $data): BookmarkEntity
    {
        return new BookmarkEntity(
            id: $data['id'] ?? null,
            user_id: $data['user_id'],
            url: $data['url'],
            page_title: $data['page_title'],
            page_capture: $data['page_capture'] ?? null,
            favicon_url: $data['favicon_url'] ?? null,
            screenshot_url: $data['screenshot_url'] ?? null,
            created_at: isset($data['created_at']) ? new \DateTimeImmutable($data['created_at']) : null,
            updated_at: isset($data['updated_at']) ? new \DateTimeImmutable($data['updated_at']) : null
        );
    }

    public static function fromBookmarkEntityToArray(BookmarkEntity $bookmark): array
    {
        return [
            'id' => $bookmark->getId(),
            'user_id' => $bookmark->getUserId(),
            'url' => $bookmark->getUrl(),
            'page_title' => $bookmark->getPageTitle(), // Fixed method name from getTitle() to getPageTitle()
            'page_capture' => $bookmark->getPageCapture(),
            'favicon_url' => $bookmark->getFaviconUrl(),
            'screenshot_url' => $bookmark->getScreenshotUrl(),
            'created_at' => $bookmark->getCreatedAt()?->format('Y-m-d H:i:s'),
            'updated_at' => $bookmark->getUpdatedAt()?->format('Y-m-d H:i:s'),
        ];
    }

    public static function fromArrayToBookmarkEntityCollection(array $data): array
    {
        return array_map(fn($item) => self::fromArrayToBookmarkEntity($item), $data);
    }

    /**
     * Converts an associative array to a BookmarkCreateUpdateDto object.
     *
     * @param array $data The associative array containing bookmark data.
     * @return array An associative array representation of the BookmarkCreateUpdateDto.
     */
    public static function fromDtoToUpdateArray(BookmarkDto $bookmarkDto): array
    {
        $bookmark = self::fromDtoToArray($bookmarkDto);
        //filter out null values
        $filteredProperties = array_filter($bookmark, fn($prop) => $prop !== null);
        //return the filtered properties
        return $filteredProperties;
    }

    public static function fromDtoToArray(BookmarkDto $bookmarkDto): array
    {
        return [
            'id' => $bookmarkDto->getId(),
            'user_id' => $bookmarkDto->getUserId(),
            'url' => $bookmarkDto->getUrl(),
            'page_title' => $bookmarkDto->getPageTitle(),
            'page_capture' => $bookmarkDto->getPageCapture(),
            'favicon_url' => $bookmarkDto->getFaviconUrl(),
            'screenshot_url' => $bookmarkDto->getScreenshotUrl(),
            'created_at' => $bookmarkDto->getCreatedAt()?->format('Y-m-d H:i:s'),
            'updated_at' => $bookmarkDto->getUpdatedAt()?->format('Y-m-d H:i:s'),
        ];
    }
}
