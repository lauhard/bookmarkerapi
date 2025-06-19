<?php

declare(strict_types=1);

namespace App\Application\Actions\Bookmark;

use App\Domain\Bookmark\BookmarkService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Responder\JsonResponder;

class BookmarkSearchAction
{

    public function __construct(
        private readonly BookmarkService $bookmarkService,
        private readonly JsonResponder $jsonResponder
    ) {}

    public function __invoke(Request $request, Response $response, array $args)
    {

        $query = $request->getQueryParams()['q'] ?? '';
        $userId = $args['userId'] ?? null;

        if ($userId === null) {
            return $this->jsonResponder->error(
                message: 'UserId is missing',
                status: 400
            );
        }

        $bookmarks = $this->bookmarkService->searchBookmarksForUser($userId, $query);
        return $this->jsonResponder->success(
            data: $bookmarks,
            status: 200
        );
    }
}
