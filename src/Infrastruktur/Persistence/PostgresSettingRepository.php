<?php

declare(strict_types=1);

namespace App\Infrastruktur\Persistence;

use PDO;
use App\Application\Dto\Setting\SettingDto;
use App\Domain\Setting\Entity\SettingEntity;
use App\Domain\Setting\Factory\SettingEntityFactory;
use App\Domain\Setting\Repository\SettingRepositoryInterface;

class PostgresSettingRepository implements SettingRepositoryInterface
{
    public function __construct(
        private PDO $pdo
    ) {}

    public function insert(SettingDto $settingDto): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO bookmarker.user_setting (user_id, theme, show_description, show_date, show_lists, show_tags)
             VALUES (:user_id, :theme, :show_description, :show_date, :show_lists, :show_tags)
             RETURNING id, created_at, updated_at'
        );

        $stmt->execute([
            ':user_id' => $settingDto->getUserId(),
            ':theme' => $settingDto->getTheme(),
            ':show_description' => $settingDto->isShowDescription(),
            ':show_date' => $settingDto->isShowDate(),
            ':show_lists' => $settingDto->isShowLists(),
            ':show_tags' => $settingDto->isShowTags()
        ]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return true;
        }
        return false;
    }

    public function update(string $userId, array $data): ?string
    {
        //update just the fields that are present in the data array
        //prepare set clause dynamically based on the data array
        $setClause = [];
        foreach ($data as $key => $value) {
            if ($value !== null) {
                $setClause[] = "$key = :$key";
            }
        }
        //add delimiter for the set clause
        $setClause = implode(', ', $setClause);

        //create the SQL statement with the dynamic set clause
        $sql = "UPDATE bookmarker.user_setting
                SET $setClause
                WHERE user_id = :user_id
                RETURNING id";
        $stmt = $this->pdo->prepare($sql);

        //bind the values dynamically
        foreach ($data as $key => $value) {
            if ($value !== null) {
                $stmt->bindValue(":$key", $value, $this->getPdoType($value));
            }
        }
        $stmt->bindValue(':user_id', $userId);
        $stmt->execute();
        $id = $stmt->fetchColumn();
        return $id !== false ? $id : null;
    }

    public function read(string $userId): ?SettingEntity
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM bookmarker.user_setting WHERE user_id = :user_id'
        );
        $stmt->execute([':user_id' => $userId]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return SettingEntityFactory::fromDBArray($data);
        }
        return null;
    }

    public function getPdoType(mixed $value): int
    {
        return match (gettype($value)) {
            'boolean' => \PDO::PARAM_BOOL,
            'integer' => \PDO::PARAM_INT,
            'NULL'    => \PDO::PARAM_NULL,
            default   => \PDO::PARAM_STR,
        };
    }
}
