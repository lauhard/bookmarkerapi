<?php

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Actions\User\UserLoginAction;
use App\Application\Actions\User\UserCreateAction;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Application\Actions\Bookmark\ListBookmarkAction;

return function (App $app) {
    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Bookarker API');
        return $response;
    });

    $app->get('/health', function (Request $request, Response $response) {
        $response->getBody()->write('OK');
        return $response->withStatus(200);
    });

    $app->group('/auth', function (RouteCollectorProxy $group) {
        $group->post('/register', UserCreateAction::class);
        $group->post('/login', UserLoginAction::class);
    });
    $app->group('/api', function (RouteCollectorProxy $group) {
        $group->get('/bookmarks', ListBookmarkAction::class);
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