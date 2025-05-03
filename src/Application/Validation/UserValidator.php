<?php

declare(strict_types=1);

namespace App\Application\Validation;

use App\Domain\Exception\ValidationException;


class UserValidator
{
    use ValidatePropertiesTrait;
    //validate password length, special characters, numbers, upper and lower case letters
    //validate email format -regex
    //validate nickname length
    public static function validateRegisterUser(array $data)
    {
        $errors = [];
        if (!isset($data['email'])) {
            $errors['email'] = 'Email is required';
        } else {
            if (!self::validateEmail($data['email'])) {
                $errors['email'] = 'Invalid email format';
            }
        }

        if (!isset($data['password'])) {
            $errors['password'] = 'Password is required';
        } else {
            if (!self::vaildatePassword($data['password'])) {
                $errors['password'] = 'Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character';
            }
        }

        if (!isset($data['nickname'])) {
            $errors['nickname'] = 'Nickname is required';
        } else {
            if (!self::validateNickname($data['nickname'])) {
                $errors['nickname'] = 'Nickname must be between 3 and 20 characters long';
            }
        }

        if (!empty($errors)) {
            throw new ValidationException(errors: $errors);
        }
    }

    public static function validateLoginUser(array $data)
    {
        $errors = [];
        if (!isset($data['email'])) {
            $errors['email'] = 'Email is required';
        } else {
            if (!self::validateEmail($data['email'])) {
                $errors['email'] = 'Invalid email format';
            }
        }

        if (!isset($data['password'])) {
            $errors['password'] = 'Password is required';
        }

        if (!empty($errors)) {
            throw new ValidationException(errors: $errors);
        }
    }
}