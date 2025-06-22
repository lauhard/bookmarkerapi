<?php

declare(strict_types=1);

namespace App\Application\Actions\List;

use App\Application\Dto\List\ListDto;
use App\Responder\JsonResponder;
use App\Domain\List\ListService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ListUpdateAction
{
    public function __construct(
        private JsonResponder $responder,
        private ListService $listService,
    ) {}

    public function __invoke(Request $request, Response $response, array $args)
    {
        $payload = $request->getParsedBody();
        $id = $args['id'] ?? null;
        //prüfe auf id und name
        if ($id === null || !isset($payload['name'])) {
            return $this->responder->error('Missing required parameters: id and name', 400);
        }
        //create ListDto from payload
        $listDto = ListDto::fromArrayToDto($payload);
        //rufe den service auf, um die Liste zu aktualisieren
        $listArray = $this->listService->updateList($id, $listDto);
        return $this->responder->success(
            data: $listArray,
            message: 'List with id ' . $id . ' updated successfully',
            status: 200
        );
    }
}
