<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\User;
use App\Domain\User\Entity\UserRegister;

interface UserRepositoryInterface
{
    public function save(UserRegister $user): ?User;
    public function delete(int $id): void;
    public function update(User $user): void;
    public function findByEmail(string $email): ?User;
    public function findByName(string $name): ?User;
    public function findById(int $id): ?User;
}
