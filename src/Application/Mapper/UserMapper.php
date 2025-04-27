<?php

declare(strict_types=1);

namespace App\Application\Mapper;

use App\Domain\User\Entity\User;
use App\Application\User\Dto\UserCreateDto;

class UserMapper
{
    /**
     * Maps the UserCreateDto to an array of values.
     * diese Methode wird aufgerufen um die User Daten in ein Array zu konvertieren
     * @param UserCreateDto $userCreateDto
     * @return array
     */
    public static function fromCreateDto(UserCreateDto $userCreateDto): array
    {
        return [
            'email' => $userCreateDto->getEmail(),
            'password' => $userCreateDto->getPassword(),
            'nickname' => $userCreateDto->getNickname(),
            'avatar_url' => $userCreateDto->getAvatarUrl(),
        ];
    }

    /**
     * Deconstructs a User object to an associative array.
     * (z.B. für API-Responses, JSON, Frontend-Kommunikation)
     */
    public function toArrayApi(User $user): array
    {
        return [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'nickname' => $user->getNickname(),
            'avatar_url' => $user->getAvatarUrl(),
            'role' => $user->getRole(),
            'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $user->getUpdatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}