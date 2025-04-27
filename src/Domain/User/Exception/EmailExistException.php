<?php

declare(strict_types=1);

namespace App\Domain\User\Exception;

use DomainException;

class EmailExistException extends \DomainException
{
    private const DEFAULT_MESSAGE = 'Email already exists';
    private const DEFAULT_CODE = 422;
    private const ERROR_CODE = 'USER_EMAIL_EXISTS';
    public function __construct(string $message = self::DEFAULT_MESSAGE, int $code = self::DEFAULT_CODE, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    public function getErrorCode(): string
    {
        return self::ERROR_CODE;
    }
}
