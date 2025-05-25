<?php

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Actions\User\UserLoginAction;
use App\Application\Actions\User\UserCreateAction;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Application\Actions\Bookmark\GetBookmarksAction;
use App\Application\Actions\Bookmark\GetBookmkarkAction;
use App\Application\Actions\Bookmark\BookmarkCreateAction;
use App\Application\Actions\Bookmark\BookmarkDeleteAction;
use App\Application\Actions\Bookmark\BookmarkUpdateAction;
use App\Application\Actions\Bookmark\GetBookmarkAction;

return function (App $app) {
    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Bookarker API');
        return $response;
    });

    $app->get('/health', function (Request $request, Response $response) {
        $response->getBody()->write('OK');
        return $response->withStatus(200);
    });

    //$app->group('/auth', function (RouteCollectorProxy $group) {
    //    $group->post('/register', UserCreateAction::class);
    //    $group->post('/login', UserLoginAction::class);
    //});
    $app->group('/api', function (RouteCollectorProxy $group) {
        $group->get('/users/{userId}/bookmarks', GetBookmarksAction::class);
        $group->get('/bookmarks/{id}', GetBookmarkAction::class);
        $group->post('/bookmarks', BookmarkCreateAction::class);
        $group->delete('/bookmarks/{id}', BookmarkDeleteAction::class);
        $group->patch('/bookmarks/{id}', BookmarkUpdateAction::class);
    });



    // Placeholder response
    //$data = [
    //    ['id' => 1, 'title' => 'Slim Framework', 'url' => 'https://www.slimframework.com'],
    //    ['id' => 2, 'title' => 'Example', 'url' => 'https://example.com'],
    //];
    //
    //$response->getBody()->write(json_encode($data));
    //return $response
    //    ->withHeader('Content-Type', 'application/json');

    // DDD - ADR
    //request wird an A

};
