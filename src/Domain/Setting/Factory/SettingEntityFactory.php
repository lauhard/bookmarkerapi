<?php

declare(strict_types=1);

namespace App\Domain\Setting\Factory;

use App\Domain\Setting\Entity\SettingEntity;
use App\Application\Dto\Setting\SettingDto;

class SettingEntityFactory
{
    //from Dto to Entity
    public static function fromDto(SettingDto $settingDto): SettingEntity
    {
        return new SettingEntity(
            id: $settingDto->getId(),
            user_id: $settingDto->getUserId(),
            theme: $settingDto->getTheme(),
            show_description: $settingDto->isShowDescription(),
            show_date: $settingDto->isShowDate(),
            show_lists: $settingDto->isShowLists(),
            show_tags: $settingDto->isShowTags(),
            created_at: $settingDto->getCreatedAt(),
            updated_at: $settingDto->getUpdatedAt()
        );
    }

    //from DB to Entity
    public static function fromDBArray(array $data): SettingEntity
    {
        return new SettingEntity(
            id: $data['id'] ?? null,
            user_id: $data['user_id'],
            theme: $data['theme'] ?? 'dark',
            show_description: $data['show_description'] ?? true,
            show_date: $data['show_date'] ?? true,
            show_lists: $data['show_lists'] ?? true,
            show_tags: $data['show_tags'] ?? true,
            created_at: isset($data['created_at']) ? new \DateTimeImmutable($data['created_at']) : null,
            updated_at: isset($data['updated_at']) ? new \DateTimeImmutable($data['updated_at']) : null
        );
    }
}
