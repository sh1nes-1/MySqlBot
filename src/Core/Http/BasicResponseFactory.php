<?php

namespace Sh1ne\MySqlBot\Core\Http;

class BasicResponseFactory implements ResponseFactory
{

    public function json(mixed $data, int $statusCode = 200, array $headers = []) : Response
    {
        return new JsonResponse($data, $statusCode, $headers);
    }

}