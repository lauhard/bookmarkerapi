<?php

declare(strict_types=1);

namespace App\Application\Dto\User;

use App\Application\Validation\ValidateProperties;
use App\Application\Validation\ValidatePropertiesTrait;
use App\Domain\Exception\ValidationException;

/**
 * Data Transfer Object for creating a new user.
 *
 * @package App\Application\User\Dto
 */

class UserLoginDto
{
    use ValidatePropertiesTrait;

    public const REQUIRED_FIELDS = ['email', 'password'];
    public const ALLOWED_FIELDS = ['email', 'password'];

    public function __construct(
        public string $email,
        public string $password,
    ) {}

    public static function fromArray(array $data): self
    {
        $errors = [];

        $requiredPropError = self::requiredProperties($data, self::REQUIRED_FIELDS);
        $allowedPropError = self::allowedProperties($data, self::ALLOWED_FIELDS);
        $errors = array_merge($requiredPropError, $allowedPropError);

        if (!empty($errors)) {
            throw new ValidationException(errors: $errors);
        }

        //this is to filter the data and only keep the allowed fields
        $filtered = array_intersect_key($data, array_flip(self::ALLOWED_FIELDS));

        return new self(
            email: $filtered['email'],
            password: $filtered['password'] ?? ""
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
}
