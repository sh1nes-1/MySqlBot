<?php

namespace Sh1ne\MySqlBot\Middleware;

use Sh1ne\MySqlBot\Core\Config\AppConfig;
use Sh1ne\MySqlBot\Core\Http\Middleware;
use Sh1ne\MySqlBot\Core\Http\Request;
use Sh1ne\MySqlBot\Core\Http\Response;

class SlackAuthorization extends Middleware
{

    public function handle(Request $request) : Response
    {
        $timestamp = $request->header('X-Slack-Request-Timestamp');

        $signature = $this->makeSignature($timestamp, $request->rawBody());

        // TODO: check if timestamp is not older than 5 minutes

        if (!$this->isSignatureValid($signature, $request)) {
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

    private function isSignatureValid(string $signature, Request $request) : bool
    {
        return hash_equals($signature, $request->header('X-Slack-Signature'));
    }

}