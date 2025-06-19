<?php

declare(strict_types=1);

namespace App\Domain\Setting\Repository;

use App\Application\Dto\Setting\SettingDto;
use App\Domain\Setting\Entity\SettingEntity;

interface SettingRepositoryInterface
{

    public function insert(SettingDto $settingDto): bool;
    public function update(string $userId, array $data): ?string;
    public function read(string $userId): ?SettingEntity;
}
