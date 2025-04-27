<?php

declare(strict_types=1);

namespace App\Application\Actions\Bookmark;

use App\Domain\Bookmark\BookmarkService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ListBookmarkAction
{

    private BookmarkService $bookmarkService;

    protected Request $request;

    protected Response $response;

    protected array $args;

    public function __construct(BookmarkService $bookmarkService)
    {
        $this->bookmarkService = $bookmarkService;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;
        $bookmarks = $this->bookmarkService->getAllBookmarks();

        $bookmarksArray = array_map(function ($bookmark) {
            return [
                'id' => $bookmark->getId(),
                'title' => $bookmark->getTitle(),
                'url' => $bookmark->getUrl(),
            ];
        }, $bookmarks);

        $response->getBody()->write(json_encode($bookmarksArray));
        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}