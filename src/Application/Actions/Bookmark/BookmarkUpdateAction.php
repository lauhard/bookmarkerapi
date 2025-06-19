<?php

declare(strict_types=1);

namespace App\Application\Actions\Bookmark;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Bookmark\BookmarkService;
use App\Application\Dto\Bookmark\BookmarkDto;
use App\Application\Validation\BookmarkValidator;
use App\Application\Validation\ListValidator;
use App\Domain\List\ListService;
use App\Responder\JsonResponder;

class BookmarkUpdateAction
{
    public function __construct(
        private BookmarkService $bookmarkService,
        private ListService $listService,
        private JsonResponder $responder
    ) {}

    public function __invoke(Request $request, Response $response, array $args)
    {
        $id = $args['id'] ?? null;
        if ($id === null) {
            return $this->responder->error('Bookmark ID is required', 400);
        }
        $payload = $request->getParsedBody();

        //the same rules as in BookmarkCreateAction so we can use the same validation
        BookmarkValidator::validateBookmark($payload);

        // Check if list_id is present in the query parameters
        ListValidator::validateList($payload);

        // Get the bookmarkListIds from the payload
        $bookmarkListIds = $payload['bookmarkListIds'] ?? null;
        // Unset the bookmarkListIds from the payload to avoid conflicts
        unset($payload['bookmarkListIds']);

        $bookmarkDto = BookmarkDto::fromArrayToDto($payload);

        $id = $this->bookmarkService->updateBookmark($id, $bookmarkDto);
        if ($id === null) {
            return $this->responder->error('Failed to update bookmark', 400);
        }

        if ($id && isset($bookmarkListIds)) {
            //update the list_bookmark
            $success = $this->listService->updateListBookmark($id, $bookmarkListIds);
            if (!$success) {
                return $this->responder->error('Failed to update list bookmark', 400);
            }
        }

        return $this->responder->success(
            data: ['id' => $id],
            status: 200,
            message: "Bookmark {$id} updated successfully"
        );
    }
}
