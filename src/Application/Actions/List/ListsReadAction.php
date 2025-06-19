<?php

declare(strict_types=1);

namespace App\Application\Actions\List;

use App\Responder\JsonResponder;
use App\Domain\List\ListService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ListsReadAction
{
    public function __construct(
        private JsonResponder $responder,
        private ListService $listService,
    ) {}

    public function __invoke(Request $request, Response $response, array $args)
    {
        $userId = $args['userId'] ?? null;
        if ($userId === null) {
            return $this->responder->error('UserID is required', 400);
        }
        // we do not need a dto
        // we can pass the user Id into the service directly
        $ListDtoCollection = $this->listService->getListForUser($userId);

        return $this->responder->success(data: $ListDtoCollection, status: 200);
    }
}
