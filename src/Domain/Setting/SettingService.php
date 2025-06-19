<?php

declare(strict_types=1);

namespace App\Domain\Setting;

use App\Domain\Setting\Repository\SettingRepositoryInterface;
use App\Application\Dto\Setting\SettingDto;

class SettingService
{
    public function __construct(
        private SettingRepositoryInterface $settingRepository
    ) {}

    public function createUserSetting(SettingDto $dto): bool
    {
        // Validate data here if necessary
        return $this->settingRepository->insert($dto);
    }

    public function updateUserSetting(string $userId, SettingDto $dto): ?string
    {
        $data = SettingDto::toArray($dto);
        return $this->settingRepository->update($userId, $data);
    }

    public function getUserSetting(string $userId): ?array
    {
        $settingEntity = $this->settingRepository->read($userId);
        if ($settingEntity === null) {
            return null;
        }

        return SettingDto::fromEntityToResponseArray($settingEntity);
    }
}
