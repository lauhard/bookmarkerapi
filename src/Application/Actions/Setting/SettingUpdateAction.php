<?php

declare(strict_types=1);

namespace App\Application\Actions\Setting;

use App\Domain\Setting\Entity\SettingEntity;
use App\Application\Dto\Setting\SettingDto;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Responder\JsonResponder;
use App\Domain\Setting\SettingService;

class SettingUpdateAction
{
    public function __construct(
        private SettingService $settingService,
        private JsonResponder $responder
    ) {}

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $userId = $args['userId'] ?? null;
        $payload = $request->getParsedBody();
        if ($userId === null) {
            return $this->responder->error('User ID is required', 400);
        }
        $dto = SettingDto::fromPayload($payload);
        $id = $this->settingService->updateUserSetting($userId, $dto);

        if ($id === null) {
            return $this->responder->error(
                'User settings not found',
                404
            );
        }

        return $this->responder->success(
            data: ['id' => $id],
            status: 200,
            message: "User settings updated successfully for user {$id}",
        );
    }
}
