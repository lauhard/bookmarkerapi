<?php

declare(strict_types=1);

namespace App\Application\Actions\Setting;

use App\Domain\Setting\Entity\SettingEntity;
use App\Application\Dto\Setting\SettingDto;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Responder\JsonResponder;
use App\Domain\Setting\SettingService;

class SettingReadAction
{
    public function __construct(
        private SettingService $settingService,
        private JsonResponder $responder
    ) {}

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $userId = $args['userId'] ?? null;
        if ($userId === null) {
            return $this->responder->error(
                'User ID is required',
                400
            );
        }

        $setting = $this->settingService->getUserSetting($userId);

        return $this->responder->success(
            data: $setting,
            status: 200
        );
    }
}
