<?php

declare(strict_types=1);

namespace App\Application\Actions\Bookmark;

use App\Responder\JsonResponder;
use App\Domain\Bookmark\BookmarkService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class GetBookmarksByListAction
{
    public function __construct(
        private JsonResponder $responder,
        private BookmarkService $bookmarkService,
    ) {}

    public function __invoke(Request $request, Response $response, array $args)
    {
        $userId = $args['userId'] ?? null;
        $listId = $args['listId'] ?? null;

        if ($userId === null || $listId === null) {
            return $this->responder->error('UserID and ListID are required', 400);
        }

        // we do not need a dto
        // we can pass the user Id and list Id into the service directly
        $bookmarks = $this->bookmarkService->getBookmarksByListIdForUser($userId, $listId);
        return $this->responder->success(data: $bookmarks, status: 200);
    }
}