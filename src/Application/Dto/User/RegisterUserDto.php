<?php

declare(strict_types=1);

namespace App\Application\Dto\User;

use App\Domain\Exception\ValidationException;

/**
 * Data Transfer Object for creating a new user.
 *
 * @package App\Application\User\Dto
 */
class RegisterUserDto
{
    public const REQUIRED_FIELDS = ['email', 'password', 'nickname'];
    public const ALLOWED_FIELDS = ['email', 'password', 'nickname', 'avatar_url'];

    public function __construct(
        public string $email,
        public string $password,
        public string $nickname,
        public ?string $avatar_url = null
    ) {}

    public static function fromArray(array $data): self
    {
        $errors = [];

        // Check missing required properties
        foreach (self::REQUIRED_FIELDS as $property) {
            if (!array_key_exists($property, $data)) {
                $errors[$property] = 'Missing required property: ' . $property;
            }
        }

        // Check invalid properties
        foreach (array_keys($data) as $property) {
            if (!in_array($property, self::ALLOWED_FIELDS, true)) {
                $errors[$property] = 'Invalid property: ' . $property;
            }
        }

        if (!empty($errors)) {
            throw new ValidationException(errors: $errors);
        }

        //this is to filter the data and only keep the allowed fields
        $filtered = array_intersect_key($data, array_flip(self::ALLOWED_FIELDS));

        return new self(
            email: $filtered['email'],
            password: $filtered['password'],
            nickname: $filtered['nickname'],
            avatar_url: $filtered['avatar_url'] ?? ""
        );
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function getNickname(): string
    {
        return $this->nickname;
    }
    public function getAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }
}
