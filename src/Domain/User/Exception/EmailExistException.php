<?php

declare(strict_types=1);

namespace App\Domain\User\Exception;

use DomainException;

class EmailExistException extends \Exception
{
    private const DEFAULT_MESSAGE = 'Emall already exists';
    private const DEFAULT_CODE = 422;
    private const ERROR_CODE = 'USER_EMAIL_EXISTS';
    private array $errors;
    public function __construct(string $message = self::DEFAULT_MESSAGE, int $code = self::DEFAULT_CODE, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errors = ['email' => $message];
    }
    public function getErrorCode(): string
    {
        return self::ERROR_CODE;
    }
    public function getErrors(): array
    {
        return $this->errors;
    }
}
