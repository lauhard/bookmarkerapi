<?php

declare(strict_types=1);

namespace App\Application\Actions\List;

use App\Application\Dto\List\ListDto;
use App\Responder\JsonResponder;
use App\Domain\List\ListService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ListReadAction
{
    public function __construct(
        private JsonResponder $responder,
        private ListService $listService,
    ) {}

    public function __invoke(Request $request, Response $response, array $args)
    {
        $listId = $args['listId'] ?? null;
        if ($listId === null) {
            return $this->responder->error('ListID is required', 400);
        }
        // we do not need a dto
        // we can pass the list Id into the service directly
        $listArray = $this->listService->getListById($listId);

        return $this->responder->success(data: $listArray, status: 200);
    }
}
