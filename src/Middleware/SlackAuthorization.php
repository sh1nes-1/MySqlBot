<?php

namespace Sh1ne\MySqlBot\Middleware;

use Sh1ne\MySqlBot\Core\Config\AppConfig;
use Sh1ne\MySqlBot\Core\Http\Middleware;
use Sh1ne\MySqlBot\Core\Http\Request;
use Sh1ne\MySqlBot\Core\Http\Response;
use function hash_equals;

class SlackAuthorization extends Middleware
{

    public function handle(Request $request) : Response
    {
        $userSignature = $request->header('X-Slack-Signature');
        if (is_null($userSignature)) {
            return $this->responseFactory->json([
                'message' => 'Unauthorized',
                'reason' => 'Missing or invalid signature header',
            ], 403);
        }

        $timestamp = $request->header('X-Slack-Request-Timestamp');
        if (is_null($timestamp)) {
            return $this->responseFactory->json([
                'message' => 'Unauthorized',
                'reason' => 'Missing or invalid timestamp header',
            ], 403);
        }

        $computedSignature = $this->makeSignature($timestamp, $request->rawBody());

        // TODO: check if timestamp is not older than 5 minutes

        if (!hash_equals($computedSignature, $userSignature)) {
            return $this->responseFactory->json([
                'message' => 'Unauthorized',
                'reason' => 'Invalid signature',
            ], 403);
        }

        return $this->next->handle($request);
    }

    private function makeSignature(string $timestamp, string $body) : string
    {
        $baseString = "v0:$timestamp:$body";

        $secret = AppConfig::getSlackSigningSecret();

        return 'v0=' . hash_hmac('sha256', $baseString, $secret);
    }

}