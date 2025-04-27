<?php

declare(strict_types=1);

namespace App\Infrastruktur\Persistence;

use PDO;
use App\Domain\User\Entity\User;
use App\Domain\User\Entity\RegisterUser;
use App\Domain\User\Factory\UserFactory;
use App\Domain\User\Repository\UserRepositoryInterface;

class PostgresUserRepository implements UserRepositoryInterface
{
    public function __construct(private PDO $pdo) {}
    public function save(RegisterUser $user): ?User
    {
        $stmt = $this->pdo->prepare("INSERT INTO bookmarker.users (email, password, nickname, avatar_url, role ) VALUES (:email, :password, :nickname, :avatar_url, :role)");
        $stmt->execute([
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'nickname' => $user->getNickname(),
            'avatar_url' => $user->getAvatarUrl(),
            'role' => $user->getRole(),
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? UserFactory::fromArray($row) : null;
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM bookmarker.users WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function update(User $user): void
    {
        $stmt = $this->pdo->prepare("UPDATE bookmarker.users SET email = :email, nickname = :nickname, avatar_url = :avatar_url WHERE id = :id");
        $stmt->execute([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'nickname' => $user->getNickname(),
            'avatar_url' => $user->getAvatarUrl(),
        ]);
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM bookmarker.users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? UserFactory::fromArray($row) : null;
    }

    public function findByNickname(string $nickname): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM bookmarker.users WHERE nickname = :nickname");
        $stmt->execute(['nickname' => $nickname]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? UserFactory::fromArray($row) : null;
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM bookmarker.users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? UserFactory::fromArray($row) : null;
    }
}
