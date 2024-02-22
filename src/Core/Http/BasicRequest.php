<?php

namespace Sh1ne\MySqlBot\Core\Http;

class BasicRequest implements Request
{

    public function uri() : string
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function method() : string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function get(string $key) : mixed
    {
        return $_GET[$key] ?? null;
    }

    public function input(string $key) : mixed
    {
        $body = $this->body();

        return $body[$key] ?? null;
    }

    public function headers() : array
    {
        return getallheaders();
    }

    public function header(string $key) : ?string
    {
        $headers = $this->headers();

        return $headers[$key] ?? null;
    }

    public function rawBody() : string
    {
        return file_get_contents('php://input');
    }

    public function body() : array
    {
        $contentType = $this->header('Content-Type');

        if ($contentType && str_contains($contentType, 'application/json')) {
            return json_decode($this->rawBody(), true);
        }

        return $_POST;
    }

}