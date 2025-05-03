<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Application\Dto\User\UserRegisterDto;
use App\Application\Dto\User\UserResponseDto;
use App\Domain\User\Entity\User;
use App\Domain\User\Factory\UserRegisterFactory;
use App\Domain\User\Exception\EmailExistException;
use App\Domain\User\Exception\NickNameExistException;
use App\Domain\User\Repository\UserRepositoryInterface;

class UserService
{
    private UserRepositoryInterface $userRepository;
    public function __construct(UserRepositoryInterface $userRepository)
    {
        // Constructor code here
        $this->userRepository = $userRepository;
    }

    public function registerUser(UserRegisterDto $user_data): ?array //was soll zurückgegeben werden
    {
        // Validate user data
        if ($this->userRepository->findByEmail($user_data->getEmail())) {
            // throw new UserAlreadyExistsException('A user with this email already exists.');
            throw new EmailExistException();
        }
        if ($this->userRepository->findByNickName($user_data->getNickname())) {
            // throw new UserAlreadyExistsException('A user with this email already exists.');
            throw new NickNameExistException();
        }

        //hash password
        $hashed_password = password_hash($user_data->getPassword(), PASSWORD_ARGON2ID);
        $user_register = UserRegisterFactory::fromDto($user_data, $hashed_password);
        // Save user to the database
        $new_user = $this->userRepository->save($user_register);
        return UserResponseDto::fromUser($new_user)->jsonSerialize();
    }

    public function loginUser(string $email, string $password): UserResponseDto|null
    {
        // Validate user data
        $user = $this->userRepository->findByEmail($email);
        if (!$user) {
            return null;
        }
        if (!password_verify($password, $user->getPassword())) {
            return null;
        }

        return UserResponseDto::fromUser($user);
    }
}
