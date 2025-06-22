<?php

declare(strict_types=1);

namespace App\Domain\List\Factory;

use App\Application\Dto\List\ListDto;
use App\Domain\List\Entity\ListEntity;

class ListFactory
{
    public static function fromArrayToListEntity(array $data): ListEntity
    {
        return new ListEntity(
            id: $data['id'] ?? null,
            user_id: $data['user_id'],
            name: $data['name'],
            is_public: $data['is_public'] ?? null,
            share_token: $data['share_token'] ?? null,
            created_at: isset($data['created_at']) ? new \DateTimeImmutable($data['created_at']) : null,
            updated_at: isset($data['updated_at']) ? new \DateTimeImmutable($data['updated_at']) : null
        );
    }

    public static function fromDto(ListDto $listDto): ListEntity
    {
        return new ListEntity(
            id: $listDto->getId(),
            user_id: $listDto->getUserId(),
            name: $listDto->getName(),
            is_public: $listDto->isPublic() ?? false,
            share_token: $listDto->getShareToken() ?? null,
            created_at: $listDto->getCreatedAt() ?? null,
            updated_at: $listDto->getUpdatedAt() ?? null
        );
    }

    public static function toArray(ListEntity|ListDto $data): array
    {
        return [
            'id' => $data->getId(),
            'user_id' => $data->getUserId(),
            'name' => $data->getName(),
            'is_public' => $data->isPublic(),
            'share_token' => $data->getShareToken(),
            'created_at' => $data->getCreatedAt()?->format('Y-m-d H:i:s'),
            'updated_at' => $data->getUpdatedAt()?->format('Y-m-d H:i:s'),
        ];
    }



    public static function fromListEntityToArray(ListEntity $list): array
    {
        return [
            'id' => $list->getId(),
            'user_id' => $list->getUserId(),
            'name' => $list->getName(),
            'is_public' => $list->isPublic(),
            'share_token' => $list->getShareToken(),
            'created_at' => $list->getCreatedAt()?->format('Y-m-d H:i:s'),
            'updated_at' => $list->getUpdatedAt()?->format('Y-m-d H:i:s'),
        ];
    }

    public static function fromArrayToListEntityCollection(array $data): array
    {
        return array_map(fn($item) => self::fromArrayToListEntity($item), $data);
    }
}
