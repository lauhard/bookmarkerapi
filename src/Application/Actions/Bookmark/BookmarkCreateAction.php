<?php

declare(strict_types=1);

namespace App\Application\Actions\Bookmark;

use App\Application\Validation\BookmarkValidator;
use App\Domain\Bookmark\BookmarkService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Dto\Bookmark\BookmarkCreateUpdateDto;
use App\Responder\JsonResponder;

class BookmarkCreateAction
{
    private BookmarkService $bookmarkService;
    private JsonResponder $jsonResponder;
    public function __construct(BookmarkService $bookmarkService, JsonResponder $jsonResponder)
    {
        $this->bookmarkService = $bookmarkService;
        $this->jsonResponder = $jsonResponder;
    }

    //invoked by the controller
    public function __invoke(Request $request, Response $response, array $args)
    {
        $payload = (array) $request->getParsedBody();

        //validate if payload is complete
        BookmarkValidator::validateBookmark($payload);

        //create a new BookmarkCreateUpdateDto object
        $bookmarkDto = BookmarkCreateUpdateDto::fromArray($payload);

        //call the service to create a new bookmark
        $bookmardId = $this->bookmarkService->createBookmark($bookmarkDto);

        if (!$bookmardId) {
            return $this->jsonResponder->error(
                message: 'Bookmark creation failed',
                status: 400
            );
        }

        $response = $this->jsonResponder->success(
            data: ['id' => $bookmardId],
            status: 201,
            message: "Bookmark {$bookmardId} created successfully",
        );
        $response = $response->withHeader('Location', "/bookmarks/{$bookmardId}");
        return $response;
        //pass a valid dto to the service
        //minimal required fields are url title and user_id
        //write bookmark to db
        //return response with status 201 and the created bookmark
    }
}
