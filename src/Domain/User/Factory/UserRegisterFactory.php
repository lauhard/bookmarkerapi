<?php

declare(strict_types=1);

namespace App\Domain\User\Factory;

use App\Application\Dto\User\UserRegisterDto;
use App\Domain\User\Entity\UserRegister;

class UserRegisterFactory
{
    /**
     * From DTO to User
     *
     * @param UserRegisterDto $data
     * @return UserRegister
     */
    public static function fromDto(UserRegisterDto $data, string $hasedPassword): UserRegister
    {
        return new UserRegister(
            $data->getEmail(),
            $hasedPassword,
            $data->getName(),
            $data->getAvatarUrl(),
        );
    }
}
