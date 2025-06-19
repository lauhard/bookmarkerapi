<?php

declare(strict_types=1);

namespace App\Application\Validation;

use App\Domain\Exception\ValidationException;

class ListValidator
{
    use ValidatePropertiesTrait;
    public static function validateList(array $data): void
    {
        $errors = [];

        //check if property exists
        if (key_exists('bookmarkListIds', $data) === false) {
            $errors['bookmarkListIds'] = 'bookmarkListIds is required';
        }

        //check if bookmarkListIds is null or empty
        if (!empty($data['bookmarkListIds'])) {
            //check if if the bookmarkListIds is at least 1 valid uuid string
            //no komma means 1 uuid
            $bookmarkListIds = $data['bookmarkListIds'];
            if (is_string($bookmarkListIds) && strpos($bookmarkListIds, ',') === false) {
                //single uuid
                if (!self::validateUUID($bookmarkListIds)) {
                    $errors['bookmarkListIds'] = 'Invalid bookmarkListIds format';
                }
            }
            if (is_string($bookmarkListIds) && strpos($bookmarkListIds, ',') !== false) {
                //multiple uuids
                $bookmarkListIdsArray = explode(',', $bookmarkListIds);
                foreach ($bookmarkListIdsArray as $id) {
                    if (!self::validateUUID($id)) {
                        $errors['bookmarkListIds'] = 'Invalid bookmarkListIds format';
                        break;
                    }
                }
            }
        }

        if (!empty($errors)) {
            throw new ValidationException(errors: $errors);
        }
    }
}
