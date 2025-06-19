<?php

declare(strict_types=1);

namespace App\Application\Dto\List;

use App\Domain\List\Entity\ListEntity;

class ListDto
{

    public function __construct(
        public ?string $id = null,
        public ?string $userId = null,
        public ?string $name = null,
        public ?bool $isPublic = null,
        public ?string $shareToken = null,
        public ?\DateTimeImmutable $createdAt = null,
        public ?\DateTimeImmutable $updatedAt = null
    ) {}

    /**
     * Converts an associative array to a ListDto object.
     *
     * @param ListEntity $data The associative array containing list data.
     * @return self A ListDto object created from the provided data.
     */
    public static function fromEntityToDto(ListEntity $data): self
    {
        return new self(
            id: $data->getId(),
            userId: $data->getUserId(),
            name: $data->getName(),
            isPublic: $data->isPublic() ?? null,
            shareToken: $data->getShareToken() ?? null,
            createdAt: $data->getCreatedAt() ?? null,
            updatedAt: $data->getUpdatedAt() ?? null,
        );
    }
    /**
     * Converts a ListDto object to an associative array.
     *
     * @param self $data The ListDto object to convert.
     * @return array An associative array representation of the ListDto.
     */
    public static function fromDtoToArray(self $data): array
    {
        return [
            'id' => $data->getId(),
            'userId' => $data->getUserId(), // Updated to use getUserId()
            'name' => $data->getName(), // Updated to use getName()
            'isPublic' => $data->isPublic(),
            'shareToken' => $data->getShareToken(), // Updated to use getShareToken()
            'createdAt' => $data->getCreatedAt()?->format('Y-m-d H:i:s'), // Updated to use getCreatedAt()
            'updatedAt' => $data->getUpdatedAt()?->format('Y-m-d H:i:s'), // Updated to use getUpdatedAt()
        ];
    }

    public static function fromEntityToArray(ListEntity $data): array
    {
        return self::fromDtoToArray(self::fromEntityToDto($data));
    }

    public static function fromArrayToDto(array $data): self
    {
        return new self(
            id: $data['id'] ?? '',
            userId: $data['userId'] ?? '',
            name: $data['name'] ?? '',
            isPublic: $data['isPublic'] ?? null,
            shareToken: $data['shareToken'] ?? null,
            createdAt: isset($data['createdAt']) ? new \DateTimeImmutable($data['createdAt']) : null,
            updatedAt: isset($data['updatedAt']) ? new \DateTimeImmutable($data['updatedAt']) : null,
        );
    }

    /**
     * Converts an array to a ListDto object.
     *
     * @param ListEntity[] $data The associative array containing list data.
     * @return self[] A ListDto object created from the provided data.
     */
    public static function fromEntitiyToDtoCollection(array $entities): array
    {
        return array_map(fn($entity) => self::fromEntityToDto($entity), $entities);
    }

    public static function fromListEntityToArrayCollection(array $entities): array
    {
        return array_map(fn($entity) => self::fromDtoToArray(self::fromEntityToDto($entity)), $entities);
    }

    public function getId(): ?string
    {
        return $this->id;
    }
    public function getUserId(): ?string
    {
        return $this->userId;
    }
    public function getName(): ?string
    {
        return $this->name;
    }
    public function isPublic(): ?bool
    {
        return $this->isPublic;
    }
    public function getShareToken(): ?string
    {
        return $this->shareToken;
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
