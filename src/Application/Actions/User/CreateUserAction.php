<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\Service\UserService;
use App\Application\Dto\User\RegisterUserDto;
use App\Application\Validation\UserValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateUserAction
{
    private UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    //invoked by the controller
    public function __invoke(Request $request, Response $response, array $args)
    {
        //get payload
        $payload = (array) $request->getParsedBody();

        //validate payload
        UserValidator::validate($payload);

        //create a new RegisterUserDto object
        //vlidate required fields
        $userData = RegisterUserDto::fromArray($payload);

        $this->userService->registerUser(userData: $userData);

        $response->getBody()->write(json_encode(['message' => 'User registered successfully']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }
}
