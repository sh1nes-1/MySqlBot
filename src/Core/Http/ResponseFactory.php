<?php

namespace Sh1ne\MySqlBot\Core\Http;

interface ResponseFactory
{

    public function json(mixed $data, int $statusCode = 200, array $headers = []) : Response;

}