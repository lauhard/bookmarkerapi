<?php

declare(strict_types=1);

namespace App\Application\Actions\List;

use App\Application\Dto\List\ListDto;
use App\Responder\JsonResponder;
use App\Domain\List\ListService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ListCreateAction
{
    public function __construct(
        private JsonResponder $responder,
        private ListService $listService,
    ) {}

    public function __invoke(Request $request, Response $response, array $args)
    {
        $payload = $request->getParsedBody();
        //prüfe auf userId und name
        if (!isset($payload['userId']) || !isset($payload['name'])) {
            return $this->responder->error('Missing required parameters: userId and name', 400);
        }
        //create ListDto from payload
        $listDto = ListDto::fromArrayToDto($payload);
        //rufe den service auf, um die Liste zu erstellen
        $listArray = $this->listService->createList($listDto);
        return $this->responder->success(
            data: $listArray,
            message: 'List created successfully',
            status: 201
        );
    }
}
