<?php

declare(strict_types=1);

namespace App\Application\Actions\Bookmark;

use App\Domain\Bookmark\BookmarkService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Responder\JsonResponder;

class GetBookmarksAction
{

    private BookmarkService $bookmarkService;
    protected array $args;
    private JsonResponder $jsonResponder;

    public function __construct(BookmarkService $bookmarkService, JsonResponder $jsonResponder)
    {
        $this->bookmarkService = $bookmarkService;
        $this->jsonResponder = $jsonResponder;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->args = $args;
        //get the user ID from the request arguments
        $userId = $args['userId'] ?? null;
        if ($userId === null) {
            return $this->jsonResponder->error(
                message: 'UserId is missing',
                status: 400
            );
        }

        $bookmarks = $this->bookmarkService->getBookmarksForUser($userId);

        return $this->jsonResponder->success(
            data: $bookmarks,
            status: 200
        );
    }
}
