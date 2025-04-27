<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class HttpErrorHandler
{
    private ResponseFactoryInterface $responseFactory;
    private int $statusCode = 500;
    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }
    public function __invoke(
        ServerRequestInterface $request,
        \Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): ResponseInterface {
        $payload = [
            'code' => $exception->getCode() ?: 500,
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ];
        $message = $displayErrorDetails ? $exception->getMessage() : 'An error occurred';
        if (method_exists($exception, 'getErrors')) {
            $this->statusCode = 422;
            /** @var Custom $exception */
            $payload['errors'] = $exception->getErrors();
        }
        $payload['error'] = $message;

        $response = $this->responseFactory->createResponse($this->statusCode);
        $response->getBody()->write(
            json_encode($payload, JSON_UNESCAPED_UNICODE)
        );

        return $response->withHeader('Content-Type', 'application/json');
    }
}
