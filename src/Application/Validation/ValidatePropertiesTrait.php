<?php

declare(strict_types=1);

namespace App\Application\Validation;


trait ValidatePropertiesTrait
{

    public static function validateEmail(string $email): bool
    {
        // Validate email format using regex
        $emailRegex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        return preg_match($emailRegex, $email) === 1;
    }
    public static function vaildatePassword(string $password): bool
    {
        // Validate password format using regex
        $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
        return preg_match($passwordRegex, $password) === 1;
    }
    public static function validateNickname(string $nickname): bool
    {
        // Validate nickname length
        return strlen($nickname) >= 3 && strlen($nickname) <= 20;
    }

    public static function requiredProperties(array $data, array $required_fields): ?array
    {
        $error = [];
        // Check missing required properties
        foreach ($required_fields as $property) {
            if (!array_key_exists($property, $data)) {
                $error[$property] = 'Missing required property: ' . $property;
            }
        }
        return $error;
    }

    public static function allowedProperties(array $data, array $allowed_fields): array
    {
        // Check for correct properties only email, password, avatar_url, nickname are allowed nothing less nothing more
        $error = [];

        // Check invalid properties
        foreach (array_keys($data) as $property) {
            if (!in_array($property, $allowed_fields, true)) {
                $error[$property] = 'Invalid property: ' . $property;
            }
        }
        return $error;
    }
}