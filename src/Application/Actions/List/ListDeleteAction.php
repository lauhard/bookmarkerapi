<?php

declare(strict_types=1);

namespace App\Application\Actions\List;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\List\ListService;
use App\Responder\JsonResponder;

class ListDeleteAction
{
    public function __construct(
        private ListService $listService,
        private JsonResponder $responder
    ) {}

    public function __invoke(Request $request, Response $response, array $args)
    {
        $id = $args['id'] ?? null;
        if ($id === null) {
            return $this->responder->error('List ID is required', 400);
        }

        $success = $this->listService->deleteList($id);
        if (!$success) {
            return $this->responder->error('Failed to delete list', 400);
        }
        return $this->responder->success(
            data: ['id' => $id],
            status: 200,
            message: 'List deleted successfully'
        );
    }
}
