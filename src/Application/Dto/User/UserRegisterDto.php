<?php

declare(strict_types=1);

namespace App\Application\Dto\User;

use App\Application\Validation\ValidatePropertiesTrait;
use App\Domain\Exception\ValidationException;

/**
 * Data Transfer Object for creating a new user.
 *
 * @package App\Application\User\Dto
 */
class UserRegisterDto
{
    use ValidatePropertiesTrait;
    public const REQUIRED_FIELDS = ['email', 'password', 'name'];
    public const ALLOWED_FIELDS = ['email', 'password', 'name', 'avatar_url'];

    public function __construct(
        public string $email,
        public string $password,
        public string $name,
        public ?string $avatar_url = null
    ) {}

    public static function fromArray(array $data): self
    {
        // Check missing required properties
        $requiredFieldError = self::requiredProperties($data, self::REQUIRED_FIELDS);
        if (!empty($requiredFieldError)) {
            throw new ValidationException(errors: $requiredFieldError);
        }
        // Check invalid properties
        $allowedFieldError = self::allowedProperties($data, self::ALLOWED_FIELDS);
        if (!empty($allowedFieldError)) {
            throw new ValidationException(errors: $allowedFieldError);
        }

        //this is to filter the data and only keep the allowed fields
        $filtered = array_intersect_key($data, array_flip(self::ALLOWED_FIELDS));

        return new self(
            email: $filtered['email'],
            password: $filtered['password'],
            name: $filtered['name'],
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
    public function getName(): string
    {
        return $this->name;
    }
    public function getAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }
}
