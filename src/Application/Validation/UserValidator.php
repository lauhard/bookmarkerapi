<?php

declare(strict_types=1);

namespace App\Application\Validation;

use App\Domain\Exception\ValidationException;


class UserValidator
{
    public static function requiredProperties(array $data, array $requiredProperties)
    {
        $errors = [];
        // Check for required properties
        foreach ($requiredProperties as $property) {
            if (!array_key_exists($property, $data)) {
                $errors[$property] = 'Missing required property: ' . $property;
            }
        }
        if (!empty($errors)) {
            throw new ValidationException(errors: $errors);
        }
    }
    public static function allowedProperties(array $data, array $allowedProperties)
    {
        $errors = [];
        // Check for correct properties only email, password, avatar_url, nickname are allowed nothing less nothing more
        foreach (array_keys($data) as $property) { //take the keys of the array
            if (!in_array($property, $allowedProperties, true)) { //check if the key is in the allowed properties
                $errors[$property] = 'Invalid property ' . $property;
            }
        }

        if (!empty($errors)) {
            throw new ValidationException(errors: $errors);
        }
    }

    //validate password length, special characters, numbers, upper and lower case letters
    //validate email format -regex
    //validate nickname length
    public static function validate(array $data)
    {
        $errors = [];
        if (!isset($data['email'])) {
            $errors['email'] = 'Email is required';
        } else {
            $emailRegex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
            if (!isset($data['email']) || !preg_match($emailRegex, $data['email'])) {
                $errors['email'] = 'Invalid email format';
            }
        }

        if (!isset($data['password'])) {
            $errors['password'] = 'Password is required';
        } else {
            //password regex
            $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
            if (!isset($data['password']) || !preg_match($passwordRegex, $data['password'])) {
                $errors['password'] = 'Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character';
            }
        }

        if (!isset($data['nickname'])) {
            $errors['nickname'] = 'Nickname is required';
        } else {
            //nickname length
            if (!isset($data['nickname']) || strlen($data['nickname']) < 3 || strlen($data['nickname']) > 20) {
                $errors['nickname'] = 'Nickname must be between 3 and 20 characters long';
            }
        }

        if (!empty($errors)) {
            throw new ValidationException(errors: $errors);
        }
    }
}
