<?php

namespace Sh1ne\MySqlBot\Middleware;

use Sh1ne\MySqlBot\Config\AppConfig;
use Sh1ne\MySqlBot\Core\Clock;
use Sh1ne\MySqlBot\Core\Http\Middleware;
use Sh1ne\MySqlBot\Core\Http\Request;
use Sh1ne\MySqlBot\Core\Http\Response;
use function hash_equals;

class SlackAuthorization extends Middleware
{

    private const MAX_REQUEST_TTL_SECONDS = 60 * 5;

    public function handle(Request $request) : Response
    {
        $timestamp = $request->header('X-Slack-Request-Timestamp');
        $userSignature = $request->header('X-Slack-Signature');

        if (!$this->isTimestampValid($timestamp)) {
            return $this->responseFactory->json([
                'message' => 'Unauthorized',
                'reason' => 'Missing or invalid timestamp header',
            ], 403);
        }

        if (is_null($userSignature)) {
            return $this->responseFactory->json([
                'message' => 'Unauthorized',
                'reason' => 'Missing signature header',
            ], 403);
        }

        $computedSignature = $this->makeSignature($timestamp, $request->rawBody());

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

    private function isTimestampValid(?string $timestamp) : bool
    {
        if (!is_numeric($timestamp)) {
            return false;
        }

        if (Clock::now() - (int) $timestamp > self::MAX_REQUEST_TTL_SECONDS) {
            return false;
        }

        return true;
    }

}