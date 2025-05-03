<?php

declare(strict_types=1);

namespace App\Domain\User\Auth;

use App\Application\Dto\Auth\AuthResultDto;

interface TokenIssuerInterface
{
    public function issueToken(AuthResultDto $user): string;
    //public function validateToken(string $token): array;
    //public function revokeToken(string $token): void;
}