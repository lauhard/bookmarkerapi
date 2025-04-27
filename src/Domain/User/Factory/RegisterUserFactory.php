<?php

declare(strict_types=1);

namespace App\Domain\User\Factory;

use App\Application\Dto\User\RegisterUserDto;
use App\Domain\User\Entity\RegisterUser;

class RegisterUserFactory
{
    /**
     * From DTO to User
     *
     * @param RegisterUserDto $data
     * @return RegisterUser
     */
    public static function fromDto(RegisterUserDto $data, string $hasedPassword): RegisterUser
    {
        return new RegisterUser(
            $data->getEmail(),
            $hasedPassword,
            $data->getNickname(),
            $data->getAvatarUrl(),
        );
    }
}
