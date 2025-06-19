<?php

declare(strict_types=1);

namespace App\Application\Dto\Bookmark;

use App\Application\Validation\ValidatePropertiesTrait;
use App\Domain\Bookmark\Entity\BookmarkEntity;
use App\Domain\Exception\ValidationException;

class BookmarkDto
{
    use ValidatePropertiesTrait;
    public const REQUIRED_FIELDS = ['url', 'pageTitle', 'userId'];
    public const ALLOWED_FIELDS = ['userId', 'id', 'url', 'pageTitle',  'pageCapture', 'faviconUrl', 'screenshotUrl', 'createdAt', 'updatedAt', 'bookmarkListIds'];

    public function __construct(
        public ?string $id = null,
        public ?string $userId = null,
        public ?string $url = null,
        public ?string $pageTitle = null,
        public ?string $pageCapture = null,
        public ?string $faviconUrl = null,
        public ?string $screenshotUrl = null,
        public ?\DateTimeImmutable $createdAt,
        public ?\DateTimeImmutable $updatedAt,
    ) {}

    /**
     * Converts an associative array to a BookmarkCreateUpdateDto object.
     *
     * @param array $data The associative array containing bookmark data.
     * @param bool $isPatch Whether the conversion is for a PATCH request (allows missing fields).
     * @return self A BookmarkCreateUpdateDto object created from the provided data.
     * @throws ValidationException If required fields are missing or if there are disallowed fields.
     */
    public static function fromArrayToDto(array $data, bool $isPatch = false): self
    {
        //if patch is true, we allow missing fields
        if (!$isPatch) {
            //check required properties
            $requiredFieldError = self::requiredProperties($data, self::REQUIRED_FIELDS);
            if (!empty($requiredFieldError)) {
                throw new ValidationException(errors: $requiredFieldError);
            }
        }

        $allowedFieldError = self::allowedProperties($data, self::ALLOWED_FIELDS);
        if (!empty($allowedFieldError)) {
            throw new ValidationException(errors: $allowedFieldError);
        }
        //validate properties
        return new self(
            id: $data['id'] ?? null,
            url: $data['url'] ?? null,
            pageTitle: $data['pageTitle'] ?? null,
            userId: $data['userId'] ?? null,
            pageCapture: $data['pageCapture'] ?? null,
            faviconUrl: $data['faviconUrl'] ?? null,
            screenshotUrl: $data['screenshotUrl'] ?? null,
            createdAt: isset($data['createdAt']) ? new \DateTimeImmutable($data['createdAt']) : null,
            updatedAt: isset($data['updatedAt']) ? new \DateTimeImmutable($data['updatedAt']) : null,
        );
    }

    /**
     * Converts the BookmarkCreateUpdateDto object to an associative array.
     *
     * @return array An associative array representation of the BookmarkCreateUpdateDto.
     */
    public function fromDtoToArray(): array
    {
        return [
            'url' => $this->url,
            'pageTitle' => $this->pageTitle,
            'userId' => $this->userId,
            'id' => $this->id,
            'pageCapture' => $this->pageCapture,
            'faviconUrl' => $this->faviconUrl,
            'screenshotUrl' => $this->screenshotUrl,
            'createdAt' => $this->createdAt?->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Converts a Bookmark object to a BookmarkCreateUpdateDto object.
     *
     * @param Bookmark $data The Bookmark object to convert.
     * @return self A BookmarkCreateUpdateDto object created from the provided data.
     */
    public static function fromBookmarkEntityToDto(BookmarkEntity $data): self
    {
        return new self(
            url: $data->getUrl() ?? null,
            pageTitle: $data->getPageTitle() ?? null,
            userId: $data->getUserId() ?? null,
            id: $data->getId() ?? null,
            pageCapture: $data->getPageCapture() ?? null,
            faviconUrl: $data->getFaviconUrl() ?? null,
            screenshotUrl: $data->getScreenshotUrl() ?? null,
            createdAt: $data->getCreatedAt() ?? null,
            updatedAt: $data->getUpdatedAt() ?? null,
        );
    }

    /**
     * Converts a bookmark array to a collection of BookmarkCreateUpdateDto objects.
     *
     * @return BookmarkCreateUpdateDto[] A collection of BookmarkCreateUpdateDto objects.
     */
    public static function fromBookmarkEntityToDtoCollection(array $data): array
    {
        return array_map(fn($item) => self::fromBookmarkEntityToDto($item), $data);
    }




    public function getId(): ?string
    {
        return $this->id;
    }
    public function getPageTitle(): ?string
    {
        return $this->pageTitle;
    }
    public function getUrl(): ?string
    {
        return $this->url;
    }
    public function getUserId(): ?string
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
}
