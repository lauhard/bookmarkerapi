<?php

declare(strict_types=1);

namespace App\Infrastruktur\Auth;

use App\Application\Dto\Auth\AuthResultDto;
use App\Application\Dto\User\UserResponseDto;
use Firebase\JWT\JWT;
use DateTimeImmutable;
use App\Domain\User\Entity\User;
use App\Domain\User\Auth\TokenIssuerInterface;


class TokenIssuer implements TokenIssuerInterface
{
    private string $secretKey;
    private string $algorithm;
    private string $expirationTime;
    private string $issuer;
    private string $audience;
    private DateTimeImmutable $notBefore;
    private DateTimeImmutable $issuedAt;
    private int $expiration;
    private string $jwtId;
    private string $jwt;

    public function __construct(array $settings)
    {
        $this->secretKey = $settings['secret'];
        $this->algorithm = $settings['algorithm'];
        $this->issuer = $settings['issuer'];
        $this->audience = $settings['audience'];
        $this->expirationTime = $settings['expiration_time'];
    }
    public function issueToken(AuthResultDto $user): string
    {
        $this->issuedAt = new DateTimeImmutable();
        $this->notBefore = new DateTimeImmutable();
        $this->expiration = $this->issuedAt->modify("+{$this->expirationTime} minutes")->getTimestamp(); // Adding expiration time

        $this->jwtId = bin2hex(random_bytes(16)); // Generate a random JWT ID
        $payload = [
            'iat' => $this->issuedAt->getTimestamp(), // Issued at
            'nbf' => $this->notBefore->getTimestamp(), // Not before
            'exp' => $this->expiration, // Expiration time
            'iss' => $this->issuer, // Issuer
            'aud' => $this->audience, // Audience
            'jti' => $this->jwtId, // JWT ID
            'uid' => $user->getId(), // User id
            'email' => $user->getEmail(), // User email
            'role' => $user->getRole(), // User role
        ];

        try {
            // Generate the JWT token
            $this->jwt = JWT::encode($payload, $this->secretKey, $this->algorithm);
        } catch (\Exception $e) {
            // Handle token generation error
            throw new \RuntimeException('Token generation failed: ' . $e->getMessage());
        }
        // Implement JWT token generation logic here
        // Use the payload to create a JWT token
        // Return the generated token as a string
        return $this->jwt;
    }
}
