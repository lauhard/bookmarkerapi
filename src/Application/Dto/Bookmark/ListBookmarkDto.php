<?php

declare(strict_types=1);

namespace App\Application\Dto\Bookmark;

use App\Application\Validation\ValidatePropertiesTrait;
use App\Domain\Bookmark\Entity\Bookmark;
use App\Domain\Bookmark\Entity\BookmarkEntity;
use App\Domain\Bookmark\Entity\ListBookmarkEntity;

class ListBookmarkDto
{
    use ValidatePropertiesTrait;

    public function __construct(
        public ?string $id = null,
        public ?string $userId = null,
        public ?string $url = null,
        public ?string $pageTitle = null,
        public ?string $pageCapture = null,
        public ?string $faviconUrl = null,
        public ?string $screenshotUrl = null,
        public ?array $lists = [],
        public ?int $sortOrder = 0,
        public ?\DateTimeImmutable $createdAt = null,
        public ?\DateTimeImmutable $updatedAt = null,
    ) {}



    /**
     * Converts a Bookmark object to a ListBookmarkDto object.
     *
     * @param ListBookmarkEntity $data The Bookmark object to convert.
     * @return self A ListBookmarkDto object created from the provided data.
     */
    public static function fromEntityToDto(ListBookmarkEntity $data): self
    {
        return new self(
            id: $data->getId() ?? null,
            userId: $data->getUserId() ?? null,
            url: $data->getUrl() ?? null,
            pageTitle: $data->getPageTitle() ?? null,
            pageCapture: $data->getPageCapture() ?? null,
            faviconUrl: $data->getFaviconUrl() ?? null,
            screenshotUrl: $data->getScreenshotUrl() ?? null,
            lists: array(
                [
                    'id' => $data->getListId() ?? null,
                    'name' => $data->getListName() ?? null,
                    'isPublic' => $data->isPublic() ?? false,
                ]
            ),
            sortOrder: $data->getSortOrder() ?? null,
            createdAt: $data->getCreatedAt() ?? null,
            updatedAt: $data->getUpdatedAt() ?? null,
        );
    }

    /**
     * Converts a ListBookmarkDto object to an array.
     *
     * @param ListBookmarkDto $bookmark The ListBookmarkDto object to convert.
     * @return array An associative array representation of the ListBookmarkDto object.
     */
    public static function fromDtoToArray(ListBookmarkDto $bookmark): array
    {
        return [
            'id' => $bookmark->id,
            'userId' => $bookmark->getUserId(),
            'url' => $bookmark->getUrl(),
            'pageTitle' => $bookmark->getPageTitle(),
            'pageCapture' => $bookmark->getPageCapture(),
            'faviconUrl' => $bookmark->getFaviconUrl(),
            'screenshotUrl' => $bookmark->getScreenshotUrl(),
            'lists' => $bookmark->getList(),
            'sortOrder' => $bookmark->getSortOrder(),
            'createdAt' => $bookmark->getCreatedAt()?->format('Y-m-d H:i:s'),
            'updatedAt' => $bookmark->getUpdatedAt()?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Converts an array to a ListBookmarkDto object.
     *
     * @param array $bookmark The bookmark array to convert.
     * @return self A ListBookmarkDto object created from the provided array.
     */
    public static function fromArrayToDto(array $bookmark): self
    {
        return new self(
            id: $bookmark['id'] ?? null,
            userId: $bookmark['userId'] ?? null,
            url: $bookmark['url'] ?? null,
            pageTitle: $bookmark['pageTitle'] ?? null,
            pageCapture: $bookmark['pageCapture'] ?? null,
            faviconUrl: $bookmark['faviconUrl'] ?? null,
            screenshotUrl: $bookmark['screenshotUrl'] ?? null,
            lists: $bookmark['lists'] ?? [],
            sortOrder: $bookmark['sortOrder'] ?? 0,
            createdAt: isset($bookmark['createdAt']) ? new \DateTimeImmutable($bookmark['created_at']) : null,
            updatedAt: isset($bookmark['updatedAt']) ? new \DateTimeImmutable($bookmark['updated_at']) : null,
        );
    }

    /**
     * Converts a bookmark array to a collection of ListBookmarkDto objects.
     *
     * @return ListBookmarkDto[] A collection of ListBookmarkDto objects.
     */
    public static function fromEntityToDtoCollection(array $data): array
    {
        $bookmarkList = array_map(fn($item) => self::fromEntityToDto($item), $data);
        return $bookmarkList;
    }

    /**
     * Converts an array of ListBookmarkDto objects to an array collection.
     *
     * @param ListBookmarkDto[] $bookmarkList
     * @return array
     */
    public static function fromDtoToArrayCollection(array $bookmarkList): array
    {
        return array_map(fn($item) => self::fromDtoToArray($item), $bookmarkList);
    }

    /**
     * Converts an array of ListBookmarkEntity objects to an array collection.
     *
     * @param ListBookmarkEntity[] $bookmarkList
     * @return array
     */
    public static function fromEntityToArrayCollection(array $bookmarkList): array
    {
        $listBookmarkDtoCollection = self::fromEntityToDtoCollection($bookmarkList);
        return self::fromDtoToArrayCollection($listBookmarkDtoCollection);
    }

    /**
     * Merges an array of ListBookmarkDto by their IDs, combining lists into a single bookmark entry.
     *
     * @param ListBookmarkDto[] $bookmarkList
     * @return ListBookmarkDto[] Merged bookmarks with combined lists.
     */
    public static function mergeBookmarkLists(array $bookmarkList): array
    {
        $mergedBookmarks = array_reduce($bookmarkList, function ($carry, $item) {
            if (!isset($carry[$item->id])) {
                $carry[$item->id] = $item;
            } else {
                $carry[$item->id]->lists[] = [
                    'id' => $item->lists[0]['id'],
                    'name' => $item->lists[0]['name'],
                    'isPublic' => $item->lists[0]['isPublic'],
                ];
            }
            return $carry;
        }, []);
        return array_values($mergedBookmarks);
    }

    public static function fromBookmarkWithListToDto(BookmarkEntity $bookmark, array $lists): self
    {
        return new self(
            id: $bookmark->getId(),
            userId: $bookmark->getUserId(),
            url: $bookmark->getUrl(),
            pageTitle: $bookmark->getPageTitle(),
            pageCapture: $bookmark->getPageCapture(),
            faviconUrl: $bookmark->getFaviconUrl(),
            screenshotUrl: $bookmark->getScreenshotUrl(),
            lists: array_map(function ($list) {
                return [
                    'id' => $list->getId(),
                    'name' => $list->getName(),
                    'isPublic' => $list->isPublic(),
                ];
            }, $lists),
            sortOrder: 0, // Default sort order, can be adjusted as needed
            createdAt: $bookmark->getCreatedAt(),
            updatedAt: $bookmark->getUpdatedAt()
        );
    }

    /**
     * Unsets specified array properties from a bookmark.
     *
     * @param array $bookmark The bookmark array.
     * @param array $fieldsToUnset The fields to unset.
     * @return array The bookmark array with specified fields unset.
     */
    public static function unsetArrayProperties(array $bookmark, array $fieldsToUnset): array
    {
        foreach ($fieldsToUnset as $field) {
            if (array_key_exists($field, $bookmark)) {
                unset($bookmark[$field]);
            }
        }
        return $bookmark;
    }

    /**
     * Sorts an array of bookmarks by created_at in descending order.
     *
     * @param Bookmark[] $bookmarks
     * @return Bookmark[]
     */
    public function sortByCreatedAtDesc(array $bookmarks): array
    {
        usort($bookmarks, function ($a, $b) {
            return $b->getCreatedAt() <=> $a->getCreatedAt();
        });
        return $bookmarks;
    }









    public function getId(): ?string
    {
        return $this->id;
    }

    public function getPageTitle(): string
    {
        return $this->pageTitle;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getPageCapture(): ?string
    {
        return $this->pageCapture;
    }
    public function getFaviconUrl(): ?string
    {
        return $this->faviconUrl;
    }
    public function getScreenshotUrl(): ?string
    {
        return $this->screenshotUrl;
    }
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
    public function getSortOrder(): int
    {
        return $this->sortOrder ?? 0;
    }
    public function getList(): ?array
    {
        return $this->lists;
    }
}
