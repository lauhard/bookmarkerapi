<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Entity\User;
use App\Application\Dto\User\RegisterUserDto;
use App\Domain\User\Factory\RegisterUserFactory;
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

    public function registerUser(RegisterUserDto $userData): ?User
    {
        // Validate user data
        if ($this->userRepository->findByEmail($userData->getEmail())) {
            // throw new UserAlreadyExistsException('A user with this email already exists.');
            throw new EmailExistException();
        }
        if ($this->userRepository->findByNickName($userData->getNickname())) {
            // throw new UserAlreadyExistsException('A user with this email already exists.');
            throw new NickNameExistException();
        }

        //hash password
        $hashedPassword = password_hash($userData->getPassword(), PASSWORD_ARGON2ID);
        $registerUser = RegisterUserFactory::fromDto($userData, $hashedPassword);
        // Save user to the database
        $user = $this->userRepository->save($registerUser);
        return $user;
    }
}
