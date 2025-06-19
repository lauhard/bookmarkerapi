<?php

use Psr\Http\Message\ServerRequestInterface;
use App\Application\Middleware\HttpErrorHandler;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

return function ($app) {

    $app->addBodyParsingMiddleware();

    $app->addRoutingMiddleware();
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);


    // Middleware für CORS
    $app->add(function (ServerRequestInterface $request, $handler) use ($app): ResponseInterface {
        $origin = $request->getHeaderLine('Origin');
        $allowedOrigins = ['http://localhost:5173', 'https://bookmarker.alau.at'];

        if ($request->getMethod() === 'OPTIONS') {
            $response = $app->getResponseFactory()->createResponse(200);
        } else {
            $response = $handler->handle($request);
        }

        // Nur wenn Origin erlaubt ist
        if (in_array($origin, $allowedOrigins)) {
            $response = $response
                ->withHeader('Access-Control-Allow-Origin', $origin)
                ->withHeader('Access-Control-Allow-Credentials', 'true')
                ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, Accept')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
                ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->withHeader('Pragma', 'no-cache')
                ->withHeader('Vary', 'Origin');
        }

        return $response;
    });


    // add json body parser
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
