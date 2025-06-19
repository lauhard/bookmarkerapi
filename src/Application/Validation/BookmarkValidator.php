<?php

declare(strict_types=1);

namespace App\Application\Validation;

use App\Domain\Exception\ValidationException;

class BookmarkValidator
{
    use ValidatePropertiesTrait;
    public static function validateBookmark(array $data): void
    {
        $errors = [];

        if (!isset($data['url'])) {
            $errors['url'] = 'URL is required';
        } else {
            if (!self::validateUrl($data['url'])) {
                $errors['url'] = 'Invalid URL format';
            }
        }

        if (!isset($data['pageTitle'])) {
            $errors['pageTitle'] = 'Page - Title is required';
        } else {
            if (!self::validateString($data['pageTitle'], 3, 255)) {
                $errors['pageTitle'] = 'Title must be between 3 and 255 characters long';
            }
        }

        if (!empty($errors)) {
            throw new ValidationException(errors: $errors);
        }
    }
}
