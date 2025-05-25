<?php

declare(strict_types=1);

namespace App\Responder;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class JsonResponder
{
    private ResponseFactoryInterface $responseFactory;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function success(array|string|null $data = null, int $status = 200, string $message = "success"): ResponseInterface
    {
        $response = $this->responseFactory->createResponse($status);
        $payload = [
            'message' => $message,
        ];
        if ($data !== null) {
            $payload['data'] = $data;
        }
        $response->getBody()->write(json_encode($payload, JSON_PRETTY_PRINT));
        return $response->withHeader(
            'Content-Type',
            'application/json'
        )->withStatus($status);
    }

    public function error(string $message, int $status = 400): ResponseInterface
    {
        $response = $this->responseFactory->createResponse($status);
        $response->getBody()->write(json_encode(['error' => $message], JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
    public function fieldError(string $message, string $field, int $status = 400): ResponseInterface
    {
        $response = $this->responseFactory->createResponse($status);
        $error = [
            'errors' => [
                $field => $message,
            ],
        ];
        $response->getBody()->write(json_encode($error, JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}
