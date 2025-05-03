<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\Service\UserService;
use App\Application\Dto\User\UserRegisterDto;
use App\Application\Validation\UserValidator;
use App\Responder\JsonResponder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserCreateAction
{
    private UserService $userService;
    private JsonResponder $jsonResponder;
    public function __construct(UserService $userService, JsonResponder $jsonResponder)
    {
        $this->userService = $userService;
        $this->jsonResponder = $jsonResponder;
    }

    //invoked by the controller
    public function __invoke(Request $request, Response $response)
    {
        //get payload
        $payload = (array) $request->getParsedBody();

        //validate payload
        UserValidator::validateRegisterUser($payload);

        //create a new UserRegisterDto object
        $userData = UserRegisterDto::fromArray($payload);

        $user = $this->userService->registerUser($userData);
        if (!$userData) {
            $this->jsonResponder->error(
                message: 'User registration failed',
                status: 400
            );
        }

        return $this->jsonResponder->success(
            data: ["user" => $user],
            status: 201
        );
    }
}