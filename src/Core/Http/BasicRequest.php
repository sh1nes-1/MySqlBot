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
        // TODO: fix for json
        return $_POST[$key] ?? null;
    }

    public function header(string $key) : ?string
    {
        $headers = getallheaders();

        return $headers[$key] ?? null;
    }

    public function rawBody() : string
    {
        // TODO: implement
        return '';
    }

    public function body() : array
    {
        // TODO: fix for json
        return $_POST;
    }

}