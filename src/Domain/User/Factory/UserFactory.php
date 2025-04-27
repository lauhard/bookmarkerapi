<?php

declare(strict_types=1);

namespace App\Domain\User\Factory;

use App\Domain\User\Entity\User;
use App\Application\Dto\User\RegisterUserDto;

class UserFactory
{
    //Mapper von Properties zu Array
    //nimmt die User Daten entgegen und erzeut ein User Objekt
    /**
     * Array data from the database
     *
     * @param array $data
     * @return User
     */
    public static function fromArray(array $data): User
    {
        return new User(
            $data['id'],
            $data['email'],
            $data['password'],
            $data['nickname'],
            $data['avatar_url'],
            $data['role'],
            new \DateTimeImmutable($data['created_at']),
            new \DateTimeImmutable($data['updated_at']),
        );
    }

    /**
     * From DTO to User
     *
     * @param RegisterUserDto $data
     * @return User
     */
    public static function fromDto(RegisterUserDto $data): User
    {
        return new User(
            null,
            $data->getEmail(),
            $data->getPassword(),
            $data->getNickname(),
            $data->getAvatarUrl(),
            'user', // default role
            new \DateTimeImmutable(),
            new \DateTimeImmutable(),
        );
    }
}
