<?php

declare(strict_types=1);

namespace App\Application\Actions\Bookmark;

use App\Domain\Bookmark\BookmarkService;
use App\Responder\JsonResponder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class BookmarkDeleteAction
{
    public function __construct(
        private BookmarkService $bookmarkService,
        private JsonResponder $responder
    ) {}
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $bookmarkId = $args['id'] ?? null;
        if ($bookmarkId === null) {
            return $this->responder->error('Bookmark ID is required', 400);
        }

        $success = $this->bookmarkService->deleteBookmark($bookmarkId);
        if (!$success) {
            return $this->responder->error('Failed to delete bookmark', 400);
        }
        return $this->responder->success(
            data: null,
            status: 200, // No Content
            message: "Bookmark {$bookmarkId} deleted successfully"
        );
    }
}
