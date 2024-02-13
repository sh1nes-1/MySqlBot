<?php

namespace Sh1ne\MySqlBot\Core;

class JsonResponse implements Response
{

    private string $text;

    private int $statusCode;

    private array $headers;

    public function __construct(mixed $data, int $statusCode = 200, array $headers = [])
    {
        $this->text = json_encode($data);
        $this->statusCode = $statusCode;
        $this->initHeaders($headers);
    }

    public function getBodyAsText() : string
    {
        return $this->text;
    }

    public function getHeaders() : array
    {
        return $this->headers;
    }

    public function getStatusCode() : int
    {
        return $this->statusCode;
    }

    private function initHeaders(array $headers) : void
    {
        $defaultHeaders = [
            'Content-Type' => 'application/json',
        ];

        $this->headers = array_merge($defaultHeaders, $headers);
    }

}