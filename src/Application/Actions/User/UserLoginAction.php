<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Responder\JsonResponder;
use App\Infrastruktur\Auth\TokenIssuer;
use App\Domain\User\Service\UserService;
use App\Application\Dto\User\UserLoginDto;
use App\Application\Dto\Auth\AuthResultDto;
use App\Domain\User\Auth\TokenIssuerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserLoginAction
{
    private JsonResponder $jsonResponder;
    private UserService $userService;
    private TokenIssuerInterface $tokenIssuer;
    function __construct(UserService $userService, JsonResponder $jsonResponder, TokenIssuerInterface $tokenIssuer)
    {
        $this->tokenIssuer = $tokenIssuer;
        $this->jsonResponder = $jsonResponder;
        $this->userService = $userService;
    }

    //validatte request
    //check if email and password are set
    //check if email exists
    //check if password is correct
    //create token
    function __invoke(Request $request, Response $response): Response
    {
        // Get the request body
        $payload = (array)$request->getParsedBody();

        $loginUser = UserLoginDto::fromArray($payload);

        $user = $this->userService->loginUser(
            email: $loginUser->getEmail(),
            password: $loginUser->getPassword()
        );
        $auth_result = new AuthResultDto(
            id: $user->getId(),
            email: $user->getEmail(),
            nickname: $user->getNickname(),
            role: $user->getRole()
        );


        if (!$user) {
            return $this->jsonResponder->error(
                message: 'Invalid email or password',
                status: 401
            );
        }
        $token = $this->tokenIssuer->issueToken($auth_result);
        $user->setToken($token);
        return $this->jsonResponder->success(
            data: ["user" => $user],
            status: 200
        );
    }
}