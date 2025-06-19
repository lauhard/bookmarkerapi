<?php

declare(strict_types=1);

namespace App\Application\Actions\Bookmark;

use App\Application\Validation\BookmarkValidator;
use App\Domain\Bookmark\BookmarkService;
use App\Domain\List\ListService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Dto\Bookmark\BookmarkDto;
use App\Application\Validation\ListValidator;
use App\Responder\JsonResponder;

class BookmarkCreateAction
{
    private BookmarkService $bookmarkService;
    private ListService $listService;
    private JsonResponder $jsonResponder;
    public function __construct(BookmarkService $bookmarkService, ListService $listService, JsonResponder $jsonResponder)
    {
        $this->bookmarkService = $bookmarkService;
        $this->listService = $listService;
        $this->jsonResponder = $jsonResponder;
    }

    //invoked by the controller
    public function __invoke(Request $request, Response $response, array $args)
    {
        $payload = (array) $request->getParsedBody();

        // Validate if payload is complete
        BookmarkValidator::validateBookmark($payload);

        // Check if list_id is present in the query parameters
        ListValidator::validateList($payload);

        // Get the bookmarkListIds from the payload
        $bookmarkListIds = $payload['bookmarkListIds'] ?? null;
        // Unset the bookmarkListIds from the payload to avoid conflicts
        unset($payload['bookmarkListIds']);

        //create a new BookmarkDto object
        $bookmarkDto = BookmarkDto::fromArrayToDto($payload);

        // Create the bookmark with Lists
        $bookmarkId = $this->bookmarkService->createBookmarkWithList(
            bookmark: $bookmarkDto,
            listIds: $bookmarkListIds
        );

        $response = $this->jsonResponder->success(
            data: ['id' => $bookmarkId],
            status: 201,
            message: "Bookmark {$bookmarkId} created successfully",
        );

        $response = $response->withHeader('Location', "/bookmarks/{$bookmarkId}");
        return $response;
    }
}
