<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\User;
use App\Domain\User\Entity\RegisterUser;

interface UserRepositoryInterface
{
    public function save(RegisterUser $user): ?User;
    public function delete(int $id): void;
    public function update(User $user): void;
    public function findByEmail(string $email): ?User;
    public function findByNickName(string $email): ?User;
    public function findById(int $id): ?User;
}
