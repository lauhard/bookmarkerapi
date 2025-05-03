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
    public static function fromArrayToUser(array $data): User
    {
        return new User(
            $data['id'],
            $data['email'],
            $data['password'] ?? null,
            $data['nickname'],
            $data['avatar_url'],
            $data['role'],
            isset($data['created_at']) ? new \DateTimeImmutable($data['created_at']) : null,
            isset($data['updated_at']) ? new \DateTimeImmutable($data['updated_at']) : null,
        );
    }
}