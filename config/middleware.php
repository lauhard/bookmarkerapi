<?php

use Psr\Http\Message\ServerRequestInterface;
use App\Application\Middleware\HttpErrorHandler;

return function ($app) {
    // Middleware für CORS
    $app->add(function ($request, $handler) {
        $response = $handler->handle($request);
        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    });
    $app->addRoutingMiddleware();
    // add json body parser
    $app->addBodyParsingMiddleware();

    $errorMiddleware = $app->addErrorMiddleware(true, true, true);
    //$customErrorHandler = function (
    //    ServerRequestInterface $request,
    //    Throwable $exception,
    //    bool $displayErrorDetails,
    //    bool $logErrors,
    //    bool $logErrorDetails
    //) use ($app) {
    //
    //    $payload = ['error' => $exception->getMessage()];
    //
    //    $response = $app->getResponseFactory()->createResponse();
    //    $response->getBody()->write(
    //        json_encode($payload, JSON_UNESCAPED_UNICODE)
    //    );
    //
    //    return $response;
    //};

    $errorMiddleware->setDefaultErrorHandler(new HttpErrorHandler($app->getResponseFactory()));
};
