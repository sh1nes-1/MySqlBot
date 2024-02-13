<?php

namespace Sh1ne\MySqlBot\Middleware;

use Sh1ne\MySqlBot\Core\AppConfig;
use Sh1ne\MySqlBot\Core\JsonResponse;
use Sh1ne\MySqlBot\Core\Middleware;
use Sh1ne\MySqlBot\Core\Request;
use Sh1ne\MySqlBot\Core\Response;

class SlackAuthorization extends Middleware
{

    public function handle(Request $request) : Response
    {
        $timestamp = $request->header('x-slack-request-timestamp');

        $body = '';

        $baseString = "v0:$timestamp:$body";
        hash_hmac('sha256', $baseString, AppConfig::getSlackSigningSecret());

        if ($request->header('X-Slack-Signature') !== 'b') {
            return new JsonResponse([
                'message' => 'Unauthorized',
            ], 403);
        }

        return $this->next->handle($request);
    }

}