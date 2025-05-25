<?php

declare(strict_types=1);

namespace App\Application\Actions\Bookmark;

use App\Domain\Bookmark\BookmarkService;
use App\Responder\JsonResponder;
use ArrayObject;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class GetBookmarkAction
{
    public function __construct(private BookmarkService $bookmarkService, private JsonResponder $jsonResponder) {}

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $bookmarkId = $args['id'] ?? null;
        if (!$bookmarkId) {
            return $this->jsonResponder->error(
                message: 'Bookmark ID is missing',
                status: 400
            );
        }

        $bookmark = $this->bookmarkService->getBookmarkById($bookmarkId);
        if (!$bookmark) {
            return $this->jsonResponder->error(
                message: 'Bookmark not found',
                status: 404
            );
        }
        //wrap bookmark into an array
        $data[0] = $bookmark;

        return $this->jsonResponder->success(
            data: $data,
            status: 200,
            message: 'Bookmark retrieved successfully'
        );
    }
}
