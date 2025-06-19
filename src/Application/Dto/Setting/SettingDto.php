<?php

declare(strict_types=1);

namespace App\Application\Dto\Setting;

use App\Domain\Setting\Entity\SettingEntity;

class SettingDto
{
    public function __construct(
        private ?string $id = null,
        private ?string $userId = null,
        private ?string $theme = null,
        private ?bool $showDescription = null,
        private ?bool $showDate =  null,
        private ?bool $showLists =  null,
        private ?bool $showTags =  null,
        private ?\DateTimeImmutable $createdAt = null,
        private ?\DateTimeImmutable $updatedAt = null,
    ) {}

    //Factory from payload to SettingDto
    public static function fromPayload(array $payload): self
    {
        return new self(
            id: $payload['id'] ?? null,
            userId: $payload['userId'] ?? null,
            theme: $payload['theme'] ?? null,
            showDescription: $payload['showDescription'] ?? null,
            showDate: $payload['showDate'] ?? null,
            showLists: $payload['showLists'] ?? null,
            showTags: $payload['showTags'] ?? null,
            createdAt: isset($payload['createdAt']) ? new \DateTimeImmutable($payload['createdAt']) : null,
            updatedAt: isset($payload['updatedAt']) ? new \DateTimeImmutable($payload['updatedAt']) : null,
        );
    }

    public static function toArray(self $settingDto): array
    {
        return [
            'id' => $settingDto->getId(),
            'user_id' => $settingDto->getUserId(),
            'theme' => $settingDto->getTheme(),
            'show_description' => $settingDto->isShowDescription(),
            'show_date' => $settingDto->isShowDate(),
            'show_lists' => $settingDto->isShowLists(),
            'show_tags' => $settingDto->isShowTags(),
            'created_at' => $settingDto->getCreatedAt()?->format(DATE_ATOM),
            'updated_at' => $settingDto->getUpdatedAt()?->format(DATE_ATOM),
        ];
    }

    // From Entity to SettingDto
    public static function fromEntity(SettingEntity $entity): self
    {
        return new self(
            id: $entity->getId(),
            userId: $entity->getUserId(),
            theme: $entity->getTheme(),
            showDescription: $entity->isShowDescription(),
            showDate: $entity->isShowDate(),
            showLists: $entity->isShowLists(),
            showTags: $entity->isShowTags(),
            createdAt: $entity->getCreatedAt(),
            updatedAt: $entity->getUpdatedAt()
        );
    }

    // Convert SettingDto to array for response using getters
    public function toResponseArray(): array
    {
        return [
            'id' => $this->getId(),
            'userId' => $this->getUserId(),
            'theme' => $this->getTheme(),
            'showDescription' => $this->isShowDescription(),
            'showDate' => $this->isShowDate(),
            'showLists' => $this->isShowLists(),
            'showTags' => $this->isShowTags(),
            'createdAt' => $this->createdAt?->format(DATE_ATOM),
            'updatedAt' => $this->updatedAt?->format(DATE_ATOM),
        ];
    }

    // Convert Entity to Response array
    public static function fromEntityToResponseArray(SettingEntity $entity): array
    {
        //use fromEntity method to create a SettingDto
        $dto = self::fromEntity($entity);
        //then return the response array
        return $dto->toResponseArray();
    }


    //set getters
    public function getId(): ?string
    {
        return $this->id;
    }
    public function getUserId(): ?string
    {
        return $this->userId;
    }
    public function getTheme(): ?string
    {
        return $this->theme;
    }
    public function isShowDescription(): ?bool
    {
        return $this->showDescription;
    }
    public function isShowDate(): ?bool
    {
        return $this->showDate;
    }
    public function isShowLists(): ?bool
    {
        return $this->showLists;
    }
    public function isShowTags(): ?bool
    {
        return $this->showTags;
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
