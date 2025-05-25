<?php

declare(strict_types=1);

namespace App\Application\Actions\Bookmark;

use App\Domain\Bookmark\Entity\Bookmark;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Bookmark\BookmarkService;
use App\Application\Dto\Bookmark\BookmarkCreateUpdateDto;
use App\Responder\JsonResponder;

class BookmarkUpdateAction
{
    public function __construct(
        private BookmarkService $bookmarkService,
        private JsonResponder $responder
    ) {}
    public function __invoke(Request $request, Response $response, array $args)
    {
        $id = $args['id'] ?? null;
        if ($id === null) {
            return $this->responder->error('Bookmark ID is required', 400);
        }
        $data = $request->getParsedBody();
        $dto = BookmarkCreateUpdateDto::fromArray($data, true);

        $id = $this->bookmarkService->updateBookmark($id, $dto);
        if ($id === null) {
            return $this->responder->error('Failed to update bookmark', 400);
        }

        return $this->responder->success(
            data: ['id' => $id],
            status: 201,
            message: "Bookmark {$id} updated successfully"
        );
    }
}
