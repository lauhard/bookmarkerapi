<?php

use App\Application\Actions\User\CreateUserAction;
use App\Application\Actions\Bookmark\ListBookmarkAction;
use Slim\App;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

return function (App $app) {
    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Bookarker API');
        return $response;
    });

    $app->get('/health', function (Request $request, Response $response) {
        $response->getBody()->write('OK');
        return $response->withStatus(200);
    });




    $app->get('/bookmarks', ListBookmarkAction::class);
    $app->post('/register', CreateUserAction::class);


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
